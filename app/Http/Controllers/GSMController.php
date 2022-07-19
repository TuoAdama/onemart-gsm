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
    const USSD_FAILED_TIMEOUT_MESSAGE = "Send USSD failed, timeout";
    const USSD_FAILED_OPERATION_NOT_SUPPORT_MESSAGE = "Send USSD failed, Operation is not supported";
    const TRANSFERT_SUCCESS_MESSAGE = "Vous avez transfere";
    const SUCCESS = "Success";
    const RESPONSE = "Response";
    const ERROR = "Error";

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

        $status  = $transfertSyntaxe->status();
        if($status != 200){
            info("Status Code:".$status);
            TransfertController::failed($transfert);
            return;
        }

        $transfertSyntaxe = json_decode($transfertSyntaxe, true)['syntaxe'];

        info('Syntaxe du transfert= '.$transfertSyntaxe);

        $transfertSyntaxe = TransfertController::formatSyntaxe($transfert, $transfertSyntaxe);

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
            $msg = $response['Message'];
            if(str_contains($msg, self::TRANSFERT_SUCCESS_MESSAGE)){
                TransfertController::success($transfert, $msg);
                if($currentSolde != null){
                    SoldeController::soldeIsChange($currentSolde);
                }
            }else {
                TransfertController::failed($transfert);
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
        
        $status = $syntaxe->status();
        info('Status code: '.$status);
        if($syntaxe->status() != 200){
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
        $response = APIController::sendByFileContent($url);
        if($response == null){
            info("Temps d'attende ecoulé");
            return null;
        }
        $response = FormatMessage::responseFormat($response);

        if ($response[self::RESPONSE] == self::SUCCESS) {
            return FormatMessage::soldeMessage($response['Message']);
        }
        return null;
    }
}
