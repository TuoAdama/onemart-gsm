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

        return array_combine(['solde', 'bonus'], [$solde, $bonus]);
    }

    public static function responseFormat($message)
    {
        info("Message:".$message);
        
        if($message == null){
            return [
                'Response' => GSMController::ERROR,
                'Message' => ''
            ];
        }
        $message = nl2br(trim($message));
        $response = explode('<br />', $message);
        $response = array_map(function ($res) {
            return trim($res);
        }, $response);
        if (str_contains($message,'Response')) {
            $result['Response'] = explode('Response: ', $response[1])[1];
        }else{
            $result['Response'] = GSMController::ERROR;
        }
        if(str_contains($message,'Message')){
            $result['Message'] = explode('Message: ', $response[2])[1];
        }else{
            $result['Message'] = "";
        }
        return $result;
    }

    public static function getMontant($separator, $tab)
    {
        $montant = trim(explode($separator, $tab)[1]);
        $montant = str_replace(' ', '', $montant);
        return $montant;
    }

    public static function getReference($message)
    {
        return substr($message, strpos($message, 'Ref') + strlen("Ref "));
    }

    public static function transfertFormat($message)
    {
        $message = array_map(function($msg){
            return trim($msg);
        }, explode('.', $message));

        $msg1 = explode('Fcfa au', $message[0]);
        $msg1 = str_replace(['Vous avez transfere','numero'], ['', ''], $msg1);
        $msg1 = str_replace(' ', '', $msg1);
        $result['montant'] = $msg1[0];
        $result['numero'] = $msg1[1];

        $msg2 = str_replace(['Fcfa', 'Votre solde actuel est '], '', $message[1]);
        $result['solde'] = str_replace(' ', '', $msg2);
        $result['reference'] = str_replace('Ref ', '', $message[2]);

        return $result;
    }
}
