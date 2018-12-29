<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMerchantTube extends Model
{
    protected $fillable = [
        'user_id', 'tube_id', 'profit_rate', 'tube_rate',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tube(){
        return $this->belongsTo(Tube::class);
    }
}
