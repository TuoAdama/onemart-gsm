<?php

namespace App\Http\Controllers;

use App\Models\Solde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoldeController extends Controller
{

    const SOLDE_SUCCESS_MESSAGE = "Votre Solde EVD est";

    public static function soldeIsChange($solde)
    {
        $solde = Solde::create($solde);
        $soldeUrl = SettingController::sendSoldeURL();
        self::LogSoldeConsultation("Transmission du solde...");
        APIController::post($soldeUrl, $solde->toArray());
    }

    public static function getSolde()
    {
        $soldeSyntaxeURL = SettingController::syntaxeSoldeURL();
        self::LogSoldeConsultation("Recupération de la syntaxe du solde... URL=".$soldeSyntaxeURL);
        $syntaxe = APIController::send($soldeSyntaxeURL);
        
        if($syntaxe == null){
            self::LogSoldeConsultation("Impossible de recupérer la syntaxe du solde");
            return null;
        }
        
        $status = $syntaxe->status();
        self::LogSoldeConsultation('Status code: '.$status);
        if($syntaxe->status() != 200){
            return null;
        }
        
        $syntaxeArray = json_decode($syntaxe, true);
        if(array_key_exists('error', $syntaxeArray)){
            self::LogSoldeConsultation("Code de recupération invalide");
            return null;
        }
        $syntaxe = $syntaxeArray['syntaxe'];

        self::LogSoldeConsultation("Syntaxe du solde = ".$syntaxe);

        $url = SettingController::gsmURL() . urlencode($syntaxe);

        self::LogSoldeConsultation("Recupération du solde... URL=".$url);
        $response = APIController::sendByFileContent($url);
        if($response == null){
            self::LogSoldeConsultation("Temps d'attende ecoulé");
            return null;
        }
        $response = FormatMessage::responseFormat($response);

        $msg = $response['Message'];

        if ($response[GSMController::RESPONSE] == GSMController::SUCCESS
            && str_contains($msg, self::SOLDE_SUCCESS_MESSAGE)
        ) {
            return FormatMessage::soldeMessage($response['Message']);
        }
        return null;
    }

    public function soldeActuel()
    {
       $solde = self::getSolde();
    }

    public static function LogSoldeConsultation($message)
    {
        Log::channel('solde_consultation')
        ->info($message);
    }

}
