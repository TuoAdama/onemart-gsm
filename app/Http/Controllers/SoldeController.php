<?php

namespace App\Http\Controllers;

use App\Models\OperationMessage;
use App\Models\Solde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoldeController extends Controller
{

    const SOLDE_LOG = "solde_consultation";

    public static function soldeIsChange(int $solde):void
    {
        self::LogSoldeConsultation("\n\nMise à jour du solde: [solde=" . $solde ."]");
        $solde = Solde::create([
            'solde' => $solde,
            'bonus' =>0
        ]);
        $soldeUrl = SettingController::sendSoldeURL();
        self::LogSoldeConsultation("Transmission du solde... URL=" . $soldeUrl);
        $result = APIController::post($soldeUrl, $solde->toArray(), self::SOLDE_LOG);
        SettingController::updateCheckSoldeByUSSD(false);
    }

    public static function getSolde(): ?int
    {
        self::LogSoldeConsultation("\n\nRecupération de la syntaxe du solde:");
        $soldeSyntaxeURL = SettingController::APIsyntaxeSoldeURL();
        $syntaxe = APIController::send($soldeSyntaxeURL, self::SOLDE_LOG);
        if ($syntaxe == null || $syntaxe->status() != 200) {
            self::LogSoldeConsultation("Impossible de recupérer la syntaxe du solde");
            return null;
        }
        $syntaxeArray = json_decode($syntaxe->body(), true);
        $response = USSDController::make($syntaxeArray['syntaxe'], self::SOLDE_LOG);
        if (OperationMessage::isSuccess($response)) {
            if (OperationMessage::isSoldeMessage($response)) {
                $soldeInfo = FormatMessage::soldeMessage($response[USSDController::MESSAGE]);
                $solde = $soldeInfo['solde'];
                self::LogSoldeConsultation("Solde = " . number_format($solde, 0, '.', ' '));
                return $solde;
            }
            return self::getSolde();
        }
        return null;
    }

    public static function LogSoldeConsultation($message)
    {
        Log::channel(self::SOLDE_LOG)
            ->info($message);
    }

    public static function getSoldeFromDB(): ?int
    {
        $solde = Solde::orderBy('id', 'desc')->first();
        if($solde){
            return $solde->solde;
        }
        return null;
    }
}
