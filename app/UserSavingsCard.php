<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSavingsCard extends Model
{
    //


    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    public function saving_bank()
    {
        return $this->belongsTo('App\CardSavingsBank','saving_bank_id');
    }
}
