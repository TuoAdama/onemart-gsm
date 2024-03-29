<?php

namespace App\Http\Controllers;

use ErrorException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class APIController extends Controller
{
    public static function send($url, $logChannel = 'single')
    {
        Log::channel($logChannel)->info($url);
        try {
            $response = Http::withHeaders(self::getHeader())->get($url);
            Log::channel($logChannel)->info('Status code: '.$response->status());
            return $response;
        } catch (ConnectionException $th) {
            return null;
        } catch (RequestException $th) {
            return null;
        }
    }

    public static function post($url, $body, string $logChannel = 'single')
    {
        Log::channel($logChannel)->info("POST: url=".$url);
        $response = Http::withHeaders(self::getHeader())->post($url, $body);
        Log::channel($logChannel)->info("Status code:".$response->status());
        return $response;
    }

    public static function sendByFileContent($url)
    {
        try {
            return file_get_contents($url, false, stream_context_create(["http" => ["timeout" => 30]]));
        } catch (ErrorException $th) {
            return null;
        }
    }

    public static function getHeader()
    {
        return [
            'Accept' => 'application/json',
            'code' => SettingController::getAuthenficationCode(),
        ];
    }
}
