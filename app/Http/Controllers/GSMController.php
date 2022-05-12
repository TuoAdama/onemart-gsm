<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Solde;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GSMController extends Controller
{

    const INSUFFICIENT_MESSAGE = "Insufficient funds";
    const REPEAT_MESSAGE = "souhaitez-vous continuer @@ cette vente";
    const SUCCESS = "Success";
    const RESPONSE = "Response";

    public static function make(Transfert $transfert)
    {
        info("\n\nExecution du transfert: [id=".$transfert->id.", status="
        .$transfert->etat->libelle.", numero=".$transfert->numero.", montant=".$transfert->montant."]");

        $previousSolde = self::getSolde();

        if ($previousSolde == null) {
            TransfertController::failed($transfert);
            info("Echec de récupération du solde");
            return;
        }
        info("Solde Precedent = ".$previousSolde['solde']);

        $transfertSyntaxeURL = SettingController::transfertSyntaxeURL();
        $transfertSyntaxe = "*410*NUMERO*MONTANT*41253#";
        $transfertSyntaxe = str_replace(["NUMERO", "MONTANT"], [$transfert->numero, $transfert->montant], $transfertSyntaxe);

        info("Envoie du transfert...");
        $message = APIController::sendByFileContent(SettingController::gsmURL() . urlencode($transfertSyntaxe));

        $response = FormatMessage::responseFormat($message);
        info("Reponse=".$response[self::RESPONSE].", Message=".$response['Message']);

        $currentSolde = self::getSolde();
        info("Solde Actuel = ".$currentSolde['solde']);

        if (str_contains($response['Message'], self::REPEAT_MESSAGE)) {
            $res = APIController::sendByFileContent(SettingController::gsmURL() . "1");
            $response = FormatMessage::responseFormat($res);
        }

        if ($response[self::RESPONSE] != self::SUCCESS && $currentSolde = null) {
            TransfertController::failed($transfert);
            return;
        }

        if ($response[self::RESPONSE] != self::SUCCESS && $currentSolde != null) {
            if ($currentSolde['solde'] != $previousSolde['solde']) {
                TransfertController::success($transfert);
                SoldeController::soldeIsChange($currentSolde);
                return;
            }
        }

        if ($response[self::RESPONSE] == self::SUCCESS) {
            if (str_contains($response['Message'], self::INSUFFICIENT_MESSAGE)) {
                TransfertController::failed($transfert);
            }else{
                TransfertController::success($transfert, $response['Message']);
                if($currentSolde != null){
                    SoldeController::soldeIsChange($currentSolde);
                }
            }
        }
    }

    public static function getSolde()
    {
        info("Recupération du solde...");
        $soldeSyntaxeURL = SettingController::getSetting('syntaxeSoldeURL')->value;

        $soldeSyntaxeURL = "*414*41253#";
        $url = SettingController::gsmURL() . urlencode($soldeSyntaxeURL);
        $response = file_get_contents($url);
        $response = FormatMessage::responseFormat($response);

        if ($response[self::RESPONSE] == self::SUCCESS) {
            return FormatMessage::soldeMessage($response['Message']);
        }
        return null;
    }
}
