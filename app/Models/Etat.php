<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const EN_COURS = "EN COURS";
    const EXECUTE = "EXECUTE";
    const EN_ATTENTE = "EN ATTENTE";
    const ECHOUE = "ECHOUE";
    const SUSPENDU = "SUSPENDU";
    const ANNULE = "ANNULE";
}
