<?php

namespace App\Http\Controllers;

use ErrorException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class APIController extends Controller
{
    public static function send($url)
    {
        try {
            return Http::get($url);
        } catch (ConnectionException $th) {
            return null;
        } catch (RequestException $th){
            return null;
        }
    }

    public static function sendByFileContent($url)
    {
        try {
            return file_get_contents($url, false, stream_context_create(["http"=>["timeout"=>10]]));
        } catch (ErrorException $th) {
            return null;   
        }
    }
}
