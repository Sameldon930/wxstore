<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 14:41
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CardCreditBank extends Model
{
    //
    protected $fillable = [
        'url', 'status','order','bank_name','single_limit','day_limit',
    ];

    public function bank()
    {
        return $this->belongsTo('App\Bank');
    }

}