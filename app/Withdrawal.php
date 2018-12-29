<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use Searchable;

    protected $fillable = [
        'user_id', 'tube_id', 'order_no', 'out_order_no', 'trade_amount', 'real_amount',
        'type', 'status', 'note', 'refuse_reason',
    ];

    const TYPE_ACCOUNT_BALANCE = 1;
    const TYPES = [
        self::TYPE_ACCOUNT_BALANCE => '账户余额提现',
    ];

//    提现中->审核   提现成功->查看   提现失败->查看原因
    const STATUS_WAITING = 1;
    const STATUS_FINISHED = 2;
    const STATUS_CANCELED = 3;
    const STATUS = [
        self::STATUS_WAITING => '提现中',
        self::STATUS_FINISHED => '提现成功',
        self::STATUS_CANCELED => '提现失败',
    ];

    public function getTradeAmountAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }
    public function getRealAmountAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function tube(){
        return $this->belongsTo(Tube::class);
    }

    public final function custom_mobile($search_item, $search_params)
    {
        $this->builder->whereHas('user', function ($query) use ($search_item) {
            $query->where('mobile', 'like', $this->request->get($search_item));
        });
    }

    public static function generateOrderNo($tube_id){
        return date('YmdHis') . $tube_id . random_int(10000, 99999);
    }

}
