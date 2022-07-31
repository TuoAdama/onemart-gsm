<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class USSDController extends Controller
{
    public static function make($syntaxe)
    {
        info("Syntaxe: ".$syntaxe);
        $url = SettingController::gsmURL().urlencode($syntaxe);
        info('URL:'.$url);
        $response = APIController::sendByFileContent($url);
        info('Message'.$response);
        return FormatMessage::responseFormat($response);
    }
}
