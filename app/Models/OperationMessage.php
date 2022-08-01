<?php

namespace App\Models;

use App\Http\Controllers\USSDController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationMessage extends Model
{

    const SOLDE_SUCCESS = "Votre Solde EVD est";
    const TRANSFERT_SUCCESS = "Vous avez transfere";
    const REPEAT_MESSAGE = "souhaitez-vous continuer";
    const INSUFFICIENT_FUNDS = "Insufficient funds";
    const OPERATION_NOT_SUPPORTED = "Send USSD failed, Operation is not supported.";
    const TIMEOUT="Send USSD failed, timeout.";
    CONST PIN_INVALID="PIN non-valide";
    const NO_MESSAGE = "NO MESSAGE";

    public static function errorsMessages():array
    {
        return [
            self::INSUFFICIENT_FUNDS,
            self::OPERATION_NOT_SUPPORTED,
            self::TIMEOUT,
            self::PIN_INVALID,
            self::NO_MESSAGE,
        ];
    }

    public static function isSuccess(array $response):bool
    {
        $res = array_filter(self::errorsMessages(), function($errorMessage) use ($response){
            return str_contains($errorMessage, $response[USSDController::MESSAGE]);
        });
        return count($res) == 0;
    }

    public static function isSoldeMessage(array $response):bool
    {
        return str_contains($response[USSDController::MESSAGE], self::SOLDE_SUCCESS);
    }

    public static function isTransfertMessage(array $response):bool
    {
        return str_contains($response[USSDController::MESSAGE], self::TRANSFERT_SUCCESS);
    }

    public static function isRepeatMessage(array $response):bool
    {
        return str_contains($response[USSDController::MESSAGE], self::REPEAT_MESSAGE);
    }
}
