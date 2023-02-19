<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class withdraw extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amont',
        'txHash',
        'date',
        ];
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
