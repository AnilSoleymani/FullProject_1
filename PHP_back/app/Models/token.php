<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class token extends Model
{

    protected $fillable = [
        'date',
        'mined',
        'with',
        'total'
    ];
    use HasFactory;
}
