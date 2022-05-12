<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public static function getSetting(String $key)
    {
       return Setting::where('key',$key)->first();
    }

    public static function transfertSyntaxeURL()
    {
        return self::getSetting("syntaxeTransfertURL")->value;
    }

    public static function gsmURL()
    {
        return self::getSetting('gsmURL')->value;
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
}
