<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class volume extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'vol',
        'mined',
        'modem_id',
    ];
    public function modem()
    {
        return $this->belongsTo(modem::class);
    }
}
