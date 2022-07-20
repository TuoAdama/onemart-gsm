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
        info("Message:" . $message);

        if ($message == null) {
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
        if (str_contains($message, 'Response')) {
            $result['Response'] = explode('Response: ', $response[1])[1];
        } else {
            $result['Response'] = GSMController::ERROR;
        }
        if (str_contains($message, 'Message')) {
            $result['Message'] = explode('Message: ', $response[2])[1];
        } else {
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
        $message = array_map(function ($msg) {
            return trim($msg);
        }, explode('.', $message));

        // Montant
        $m1 = $message[0];
        $len1 = strlen("Vous avez transfere ");
        $pos = strpos($m1, "Fcfa");
        $montant = substr($m1, $len1, $pos - $len1);
        $result['montant'] = str_replace(' ', '', $montant);

        // Numero
        $pos = strpos($m1, 'numero');
        $len2 = strlen('numero ');
        $result['numero'] = substr($m1, $pos+$len2, 10);

        $msg2 = str_replace(['Fcfa', 'Votre solde actuel est '], '', $message[1]);
        $result['solde'] = str_replace(' ', '', $msg2);
        $result['reference'] = str_replace('Ref ', '', $message[2]);
        return $result;
    }

    public static function formatFlashMessage($message)
    {
        $str = "Vous avez transfere MONTANT Fcfa au numero NUMERO";
        $msg = self::transfertFormat($message);
        $montant = number_format($msg['montant'], 0, '', ' ');

        return str_replace(['MONTANT', 'NUMERO'], [$montant, $msg['numero']], $str);
    }
}
