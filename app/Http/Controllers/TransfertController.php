<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Message;
use App\Models\Setting;
use App\Models\Transfert;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TransfertController extends Controller
{
    public static function getTransfertOnline(): array
    {
        self::LogStoreTransfert("\n\n\nRécuperation des transferts en ligne [Recuperation]");

        $setting = SettingController::appOnlineURL();

        if ($setting != null) {
            self::LogStoreTransfert("URL=" . $setting);
            $response = APIController::send($setting);
            self::LogStoreTransfert("Status code: ". $response->status());
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

                self::LogStoreTransfert("[Transfert: id=" . $transfert['id'] . ", numero=" . $transfert['numero'] . ", montant=" . $transfert['montant'] . "]");

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
        info("\n\n----------------------------------------------------------------");
        $etat_ids = Etat::whereIn('libelle', ['EN COURS', 'ECHOUE'])
            ->get()
            ->pluck('id')
            ->toArray();
        $transfert = Transfert::whereIn('etat_id', $etat_ids)
                        ->orderBy('updated_at')
                        ->first();
        info("Recuperation du transfert:", $transfert->toArray());
        GSMController::make($transfert);
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

        $reference = null;

        if ($message != null) {
            Message::create([
                'transfert_id' => $transfert->id,
                'sms' => $message
            ]);

            $reference = FormatMessage::getReference($message);
        }

        $transfert->transfert_id = $transfert->id;
        $transfert->reference = $reference;
        $transfert->sms = $message;
        info("Envoie du transfert en ligne...");
        $result = APIController::post(SettingController::smsStorage(), $transfert->toArray());
        info("Status:", [$result->status()]);
    }

    public static function formatSyntaxe($transfert, $syntaxe)
    {
       return str_replace(["NUMERO", "MONTANT"], [$transfert->numero, $transfert->montant], $syntaxe);
    }

    public static function LogStoreTransfert($message)
    {
        Log::channel('store_transfert')->info($message);
    }
}
