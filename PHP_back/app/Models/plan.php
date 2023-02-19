<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dailyVol',
        'monthlyVol',
    ];
    public function modems()
    {
        return $this->hasMany(modem::class);
    }
}
