<?php

namespace App\Http\Controllers;

use App\Models\OperationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class USSDController extends Controller
{
    const SUCCESS = "Success";
    const RESPONSE = "Response";
    const ERROR = "Error";
    const MESSAGE = "Message";

    public static function make(string $syntaxe, $logChannel = 'single'):array
    {
        Log::channel($logChannel)->info("Syntaxe: ".$syntaxe);
        $url = SettingController::gsmURL().urlencode($syntaxe);
        Log::channel($logChannel)->info('URL:'.$url);
        $response = APIController::sendByFileContent($url);
        Log::channel($logChannel)->info('Message'.$response);
        $USSDResponse = FormatMessage::responseFormat($response);
        self::USSDCheck($USSDResponse);
        return $USSDResponse;
    }

    public static function USSDCheck(array $response): int
    {
        $setting = SettingController::numOfFailed();
        $value = OperationMessage::isSuccess($response) ? 0 
                    : ++$setting->value;
        $setting->update(compact('value'));
        return $value;
    }
}
