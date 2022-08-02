<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public static function getHost(): string
    {
        return self::getSetting('host');
    }

    public static function getSetting(String $key): string
    {
        return Setting::where('key', $key)->first()->value;
    }

    public static function APItransfertSyntaxeURL(): string
    {
        return self::url(self::getSetting("syntaxeTransfertURL"));
    }

    public static function gsmURL(): string
    {
        return self::getSetting('gsmURL');
    }

    public static function smsStorage(): string
    {
        return self::url(self::getSetting("smsStorage"));
    }

    public static function getAuthenficationCode(): string
    {
        return self::getSetting('authentificationAPI');
    }

    public static function appOnlineURL(): string
    {
        return self::url(self::getSetting('appOnlineURL'));
    }

    public static function APIsyntaxeSoldeURL(): string
    {
        return self::url(self::getSetting('syntaxeSoldeURL'));
    }

    public static function sendSoldeURL(): string
    {
        return self::url(self::getSetting('soldeURL'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::where('key', $request->get('key'))->first();
        if ($setting != null) {
            $setting->value = $request->get('value');
            $setting->save();
            return redirect()->back()->with('success', 'Modification effectuée');
        }
        abort(404);
    }

    public static function url(string $path): string
    {
        return self::getHost() . $path;
    }

    public static function numOfFailed(): Setting
    {
        return Setting::where('key', 'number_of_failed')->first();
    }

    public static function getNumOfFailed(): int
    {
        return self::getSetting('number_of_failed');
    }

    public static function checkSoldeByUSSD(): bool
    {
        return self::getSetting('check_solde_by_ussd');
    }

    public static function updateCheckSoldeByUSSD(bool $check): bool
    {
        Setting::where('key', 'check_solde_by_ussd')
            ->update([
                'value' => $check ? 1 : 0
            ]);
        return $check;
    }

    public static function nombreTentative(): int
    {
        return self::getSetting('max_failed');
    }

    public static function restartSysteme():bool
    {
        return self::getNumOfFailed() == self::nombreTentative();
    }

    public static function sendNotification(Request $request)
    {
        return response()->json([
            'notify' => self::restartSysteme(),
        ]);
    }
}
