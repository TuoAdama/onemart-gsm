<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function etat()
    {
        return $this->belongsTo(Etat::class);
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->locale('fr')->isoFormat('LLLL');
    }
}
