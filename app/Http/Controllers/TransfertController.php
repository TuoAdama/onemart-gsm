<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use App\Models\Message;
use App\Models\OperationMessage;
use App\Models\Setting;
use App\Models\Solde;
use App\Models\Transfert;
use Illuminate\Http\RedirectResponse;
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
            if ($response == null) {
                self::LogStoreTransfert("url inacessible");
                return [];
            }
            self::LogStoreTransfert("Status code: " . $response->status());
            if ($response->status() == 200) {
                return json_decode($response->body(), true);
            }
        }
        return [];
    }

    public static function store(): void
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

    public static function make(): void
    {
        if (!SettingController::restartSysteme()) {
            info("\n\n----------------------------------------------------------------");
            $etat_ids = Etat::where('libelle', 'EN COURS')
                ->get()
                ->pluck('id')
                ->toArray();
            $transfert = Transfert::whereIn('etat_id', $etat_ids)
                ->orderBy('updated_at')
                ->first();
            if ($transfert != null) {
                $result = self::makeTransfertUSSD($transfert);
                if ($result) {
                    self::success($transfert, $result['message']);
                    SoldeController::soldeIsChange($result['solde']);
                } else {
                    self::failed($transfert);
                }
            } else {
                info("Aucun transfert en cours");
            }
        }
    }

    public static function failed(Transfert $transfert): void
    {
        $transfert->etat_id = EtatController::echoue()->id;
        $transfert->save();
    }
    public static function success(Transfert $transfert, string $message = null): void
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

    public static function formatSyntaxe(Transfert $transfert, string $syntaxe): string
    {
        return str_replace(["NUMERO", "MONTANT"], [$transfert->numero, $transfert->montant], $syntaxe);
    }

    public static function LogStoreTransfert(string $message): void
    {
        Log::channel('store_transfert')->info($message);
    }

    public function updateTransfert(int $transfert_id, int $etat_id): RedirectResponse
    {
        $trans = Transfert::find($transfert_id);
        $trans->etat_id = $etat_id;
        $trans->save();
        return redirect()->back();
    }

    public static function makeTransfertUSSD(Transfert $transfert): ?array
    {
        $previousSolde = SoldeController::getSoldeFromDB();
        if (SettingController::checkSolde()) {
            $previousSolde = SoldeController::getSolde();
        }
        if ($previousSolde == null) {
            return null;
        }
        info("Previous solde: " . $previousSolde);
        info("Transfert: ", $transfert->toArray());
        $response = APIController::send(SettingController::APItransfertSyntaxeURL());
        if ($response == null || $response->status() != 200) {
            info("Impossible de recupérer la syntaxe");
            return null;
        }
        $syntaxeResponse = json_decode($response, true)['syntaxe'];
        $syntaxe = self::formatSyntaxe($transfert, $syntaxeResponse);
        $USSDReponse = self::transfertOperation($transfert, $syntaxe);
        $message = null;
        $currentSolde = null;
        if (count($USSDReponse)) {
            $message = $USSDReponse[USSDController::MESSAGE];
            $currentSolde = FormatMessage::transfertFormat($message)['solde'];
        } else {
            $currentSolde = SoldeController::getSolde();
            if ($currentSolde == null || ($currentSolde == $previousSolde)) {
                return null;
            }
        }
        return [
            'solde' => $currentSolde,
            'message' => $message
        ];
    }

    public static function transfertOperation(string $syntaxe): array
    {
        $USSDReponse = USSDController::make($syntaxe);
        if (OperationMessage::isTransfertMessage($USSDReponse)) {
            return $USSDReponse;
        } else if (OperationMessage::isRepeatMessage($USSDReponse)) {
            return self::transfertOperation("1");
        }
        return [];
    }
}
