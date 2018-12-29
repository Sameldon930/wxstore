<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 17:09
 */

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingCard extends Model
{
    //    ******软删除需求******
    use Searchable, SoftDeletes;
    protected $table='user_savings_cards';

    protected $dates = ['delete_at'];
//    *******软删除需求******

    protected $fillable = [
        'user_id', 'saving_bank_id','account_no','account_name','opening_account',
    ];

}