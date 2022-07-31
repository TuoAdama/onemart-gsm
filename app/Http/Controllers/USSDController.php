<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class USSDController extends Controller
{
    public static function make($syntaxe)
    {
        dd(SettingController::gsmURL());
    }
}
