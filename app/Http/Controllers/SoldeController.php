<?php

namespace App\Http\Controllers;

use App\Models\Solde;
use Illuminate\Http\Request;

class SoldeController extends Controller
{
    public static function soldeIsChange($solde)
    {
        $solde = Solde::create($solde);
        $soldeUrl = SettingController::soldeURL();
        info("Transmission du solde...");
        APIController::post($soldeUrl, $solde->toArray());
    }
}
