<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solde extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->locale('fr')->diffForHumans();
    }
}
