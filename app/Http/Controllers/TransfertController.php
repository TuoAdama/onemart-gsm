<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Setting;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TransfertController extends Controller
{
    public static function getTransfertOnline():array
    {
        info("Recupération des transferts en ligne");

        $setting = SettingController::getSetting('appOnlineURL');
        if($setting != null ){
            $response = APIController::send($setting->value);
            if($response->status() == 200){
                return json_decode($response->body(), true);
             }
             return [];
        }
        return [];
    }

    public static function store()
    {
        $transferts = self::getTransfertOnline();
        $etat_id = Etat::where('libelle','EN COURS')->first()->id;
        foreach ($transferts as $transfert) {
            $res = Transfert::find($transfert['id']);
            if($res == null ){

                info("[Transfert: id=".$transfert['id'].", numero=".$transfert['id'].", montant=".$transfert['montant']."]");
                
                Transfert::create([
                    'id' => $transfert['id'],
                    'numero' => $transfert['numero'],
                    'montant' => $transfert['montant'],
                    'etat_id' => $etat_id,
                ]);
            }
        }
    }
}
