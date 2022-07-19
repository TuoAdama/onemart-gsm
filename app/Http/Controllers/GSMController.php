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
        info("\n\n----------------------------------------------------------------");
        
        info("Execution du transfert: [id=".$transfert->id.", status="
        .$transfert->etat->libelle.", numero=".$transfert->numero.", montant=".$transfert->montant."]");

        $transfertSyntaxeURL = SettingController::transfertSyntaxeURL();
        info("Récuperation de la syntaxe du transfert... URL=".$transfertSyntaxeURL);
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

        $msg = $response['Message'];
        if ($response[self::RESPONSE] == self::SUCCESS && str_contains($msg, self::TRANSFERT_SUCCESS_MESSAGE)) {
            TransfertController::success($transfert, $msg);
        }else {
            TransfertController::failed($transfert);
        }
    }
}
