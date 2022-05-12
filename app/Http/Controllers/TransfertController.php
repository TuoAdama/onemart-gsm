<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Message;
use App\Models\Setting;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TransfertController extends Controller
{
    public static function getTransfertOnline(): array
    {
        info("\n\n\nRécuperation des transferts en ligne [Recuperation]");
        
        $setting = SettingController::getSetting('appOnlineURL');
        
        if ($setting != null) {
            info("URL=".$setting->value);
            $response = APIController::send($setting->value);
            if ($response->status() == 200) {
                return json_decode($response->body(), true);
            }
            return [];
        }
        return [];
    }

    public static function store()
    {
        $transferts = self::getTransfertOnline();
        $etat_id = Etat::where('libelle', 'EN COURS')->first()->id;
        foreach ($transferts as $transfert) {
            $res = Transfert::find($transfert['id']);
            if ($res == null) {

                info("[Transfert: id=" . $transfert['id'] . ", numero=" . $transfert['id'] . ", montant=" . $transfert['montant'] . "]");

                Transfert::create([
                    'id' => $transfert['id'],
                    'numero' => $transfert['numero'],
                    'montant' => $transfert['montant'],
                    'etat_id' => $etat_id,
                ]);
            }
        }
    }

    public static function make()
    {
        $etat_ids = Etat::whereIn('libelle', ['EN COURS', 'ECHOUE'])->get()
            ->pluck('id')->toArray();
        $transferts = Transfert::whereIn('etat_id', $etat_ids)->get();
        $transferts->each(function ($transfert) {
            GSMController::make($transfert);
        });
    }

    public static function failed(Transfert $transfert)
    {
        $transfert->etat_id = EtatController::echoue()->id;
        $transfert->save();
    }
    public static function success(Transfert $transfert, $message = null)
    {
        $transfert->etat_id = EtatController::execute()->id;
        $transfert->save();

        if($message != null){
            Message::create([
                'transfert_id' => $transfert->id,
                'sms' => $message
            ]);
        }
    }
}
