<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormatMessage extends Controller
{
    public static function soldeMessage($message)
    {
        $tab = explode('Fcfa', $message);

        $solde = self::getMontant("Votre Solde EVD est ", $tab[0]);
        $bonus = self::getMontant("et vous avez un bonus de ", $tab[1]);

        return array_combine(['solde','bonus'], [$solde, $bonus]);
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

    public static function getMontant($separator, $tab)
    {
        $montant = trim(explode($separator, $tab)[1]);
        $montant = str_replace(' ','',$montant);
        return $montant;
    }
}
