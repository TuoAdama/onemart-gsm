<?php

namespace App\Http\Controllers;

use App\Models\Solde;
use Illuminate\Http\Request;

class SoldeController extends Controller
{
    public static function soldeIsChange($solde)
    {
        Solde::create($solde);
    }
}
