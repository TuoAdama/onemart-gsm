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
    const REPEAT_MESSAGE = "souhaitez-vous continuer";
    const SUCCESS = "Success";
    const RESPONSE = "Response";

    public static function make(Transfert $transfert)
    {
        info("----------------------------------------------------------------");
        
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
        info("Récuperation de la syntaxe du transfert... URL=".$transfertSyntaxeURL."\n");
        $transfertSyntaxe = APIController::send($transfertSyntaxeURL);
        
        if ($transfertSyntaxe == null) {
            info("Impossible de récuperer la syntaxe du transfert");
            TransfertController::failed($transfert);
            return;
        }

        $transfertSyntaxe = json_decode($transfertSyntaxe, true)['syntaxe'];

        info('Syntaxe du transfert= '.$transfertSyntaxe);

        $transfertSyntaxe = str_replace(["NUMERO", "MONTANT"], [$transfert->numero, $transfert->montant], $transfertSyntaxe);

        info("Envoie du transfert...");
        $gsmURL = SettingController::gsmURL();
        info("url=$gsmURL".urlencode($transfertSyntaxe));
        $message = APIController::sendByFileContent($gsmURL.urlencode($transfertSyntaxe));
        
        $response = FormatMessage::responseFormat($message);
        info("Reponse=".$response[self::RESPONSE].", Message=".$response['Message']);
        
        if (str_contains($response['Message'], self::REPEAT_MESSAGE)) {
            $res = APIController::sendByFileContent(SettingController::gsmURL() . "1");
            $response = FormatMessage::responseFormat($res);
        }
        $currentSolde = self::getSolde();
        info("Solde Actuel = ".$currentSolde['solde']);

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
        $soldeSyntaxeURL = SettingController::syntaxeSoldeURL();
        info("Recupération de la syntaxe du solde... URL=".$soldeSyntaxeURL);
        $syntaxe = APIController::send($soldeSyntaxeURL);
        if($syntaxe == null){
            info("Impossible de recupérer la syntaxe du solde");
            return null;
        }
        
        $syntaxeArray = json_decode($syntaxe, true);
        if(array_key_exists('error', $syntaxeArray)){
            info("Code de recupération invalide");
            return null;
        }
        $syntaxe = $syntaxeArray['syntaxe'];

        info("Syntaxe du solde = ".$syntaxe);

        $url = SettingController::gsmURL() . urlencode($syntaxe);

        info("Recupération du solde... URL=".$url);
        $response = file_get_contents($url);
        $response = FormatMessage::responseFormat($response);

        if ($response[self::RESPONSE] == self::SUCCESS) {
            return FormatMessage::soldeMessage($response['Message']);
        }
        return null;
    }
}
