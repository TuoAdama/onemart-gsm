<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etat extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const EN_COURS = 1;
    const EXECUTER = 2;
    const ECHOUE = 3;
    const ANNULER = 5;
}
