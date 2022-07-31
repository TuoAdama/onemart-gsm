<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationMessage extends Model
{

    const SOLDE_SUCCESS = "Votre Solde EVD est";
    const SOLDE_TRANSFERT = "Vous avez transfere";
    const REPEAT_MESSAGE = "souhaitez-vous continuer";
    const INSUFFICIENT_FUNDS = "Insufficient funds";
    const OPERATION_NOT_SUPPORTED = "Send USSD failed, Operation is not supported.";
    const TIMEOUT="Send USSD failed, timeout.";
    CONST PIN_INVALID="PIN non-valide";

    public static function errorsMessages()
    {
        return [
            self::INSUFFICIENT_FUNDS,
            self::OPERATION_NOT_SUPPORTED,
            self::TIMEOUT,
            self::PIN_INVALID
        ];
    }

    public static function isErrorMEsssage($message)
    {
        $res = array_filter(self::errorsMessages(), function($errorMessage) use ($message){
            return str_contains($errorMessage, $message);
        });
        return count($res) != 0;
    }
}
