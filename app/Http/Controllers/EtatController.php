<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use Illuminate\Http\Request;

class EtatController extends Controller
{
    public static function echoue()
    {
        return self::getEtatByLibelle('ECHOUE');
    }

    public static function enCours()
    {
        return self::getEtatByLibelle('EN COURS');
    }

    public static function getEtatByLibelle(String $libelle)
    {
        return Etat::where('libelle', $libelle)->first();
    }

    public static function execute()
    {
        return self::getEtatByLibelle('EXECUTE');
    }
}
