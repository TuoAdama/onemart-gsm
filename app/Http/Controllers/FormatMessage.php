<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormatMessage extends Controller
{
    public static function soldeMessage($message)
    {
        $message = preg_replace("/[^0-9]+/", "&", $message);
        $message = explode('&', $message);
        $message = array_values(array_filter($message, function($m){
            return trim($m) != '';
        }));

        return array_combine(['solde','bonus'], [$message[0], $message[1]]);
    }

    public static function responseFormat($message)
    { 
        $message = nl2br(trim($message));
        $response = explode('<br />', $message);
        $response = array_map(function($res){
            return trim($res);
        }, $response);

        $result = explode('Response: ', $response[1])[1];
        $message = explode('Message: ', $response[2])[1];
       
        return [
            'Response' => $result,
            'Message' => $message,
        ];
    }
}
