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
}
