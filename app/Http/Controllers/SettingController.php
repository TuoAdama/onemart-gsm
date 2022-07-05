<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public static function getHost()
    {
        return self::getSetting('host');
    }

    public static function getSetting(String $key)
    {
       return Setting::where('key',$key)->first()->value;
    }

    public static function transfertSyntaxeURL()
    {
        return self::url(self::getSetting("syntaxeTransfertURL"));
    }

    public static function gsmURL()
    {
        return self::getSetting('gsmURL');
    }

    public static function smsStorage()
    {
        return self::url(self::getSetting("smsStorage"));
    }

    public static function getAuthenficationCode()
    {
        return self::getSetting('authentificationAPI');
    }

    public static function appOnlineURL()
    {
        return self::url(self::getSetting('appOnlineURL'));
    }

    public static function syntaxeSoldeURL()
    {
        return self::url(self::getSetting('syntaxeSoldeURL'));
    }

    public static function sendSoldeURL()
    {
        return self::url(self::getSetting('soldeURL'));
    }

    public function update(Request $request)
    {
        $setting = Setting::where('key', $request->get('key'))->first();
        if($setting != null){
            $setting->value = $request->get('value');
            $setting->save();
            return redirect()->back()->with('success', 'Modification effectuée');
        }
        abort(404);
    }

    public static function url($path)
    {
        return self::getHost().$path;   
    }
}
