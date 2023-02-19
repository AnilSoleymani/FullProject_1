<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modem extends Model
{
    //protected $fillable = ['title', 'package', 'date', 'sbn', 'text','user_id','user_sub'];
    use HasFactory;
    protected $fillable = [
        'MCID',
        'user_id',
        'plan_id',
    ];

    public function User()
    {
        return  $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(plan::class);
    }

    public function volumes()
    {
        return $this->hasMany(volume::class);
    }
}
