<?php

namespace App\Http\Controllers;

use App\Models\Etat;
use Illuminate\Http\Request;

class EtatController extends Controller
{
    public static function echoue(): Etat
    {
        return self::getEtatByLibelle('ECHOUE');
    }

    public static function enCours(): Etat
    {
        return self::getEtatByLibelle('EN COURS');
    }

    public static function annule(): Etat
    {
        return self::getEtatByLibelle('ANNULE');
    }

    public static function getEtatByLibelle(String $libelle): Etat
    {
        return Etat::where('libelle', $libelle)->first();
    }

    public static function execute(): Etat
    {
        return self::getEtatByLibelle('EXECUTE');
    }
}
