<?php

namespace App;

use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Searchable,CommonExportTrait;

    const STATUS_1 = 1;
    const STATUS_2 = 2;
    const STATUS = [
        self::STATUS_1 => '待定状态1',
        self::STATUS_2 => '待定状态2',
    ];

    const PAY_STATUS_UNPAY = 1;
    const PAY_STATUS_PAID = 2;
    const PAY_STATUS_FAILED = 3;
    const PAY_STATUS = [
        self::PAY_STATUS_UNPAY => '未支付',
        self::PAY_STATUS_PAID => '已支付',
        self::PAY_STATUS_FAILED => '支付失败',
    ];

    const IS_SETTLE_WAITING = 0;
    const IS_SETTLE_SETTLED = 1;
    const IS_SETTLE = [
        self::IS_SETTLE_WAITING => '待结算',
        self::IS_SETTLE_SETTLED => '已结算',
    ];


    protected $fillable = [
        'user_id', 'order_no', 'out_order_no', 'merchant_out_order_no','notify_url', 'channel_id','is_settle', 'status', 'body',
        'pay_status', 'trade_amount', 'real_amount', 'paid_at', 'note', 'request', 'response', 'snap'
    ];

    protected $hidden = [
        'snap', 'request', 'response',
    ];

    public function scopeUnpay($query){
        return $query->where('pay_status', self::PAY_STATUS_UNPAY);
    }
    public function scopePaid($query){
        return $query->where('pay_status', self::PAY_STATUS_PAID);
    }
    public function scopeFailed($query){
        return $query->where('pay_status', self::PAY_STATUS_FAILED);
    }
    public function scopeWaitingSettle($query){
        return $query->where('is_settle', self::IS_SETTLE_WAITING);
    }
    public function scopeCreateDate($query, Carbon $date){
        return $query->whereBetween('created_at', [$date->startOfDay(), $date->copy()->endOfDay()]);
    }
    public function scopePaidDate($query, Carbon $date){
        return $query->whereBetween('paid_at', [$date->startOfDay(), $date->copy()->endOfDay()]);
    }
    public function scopeTube($query, $tube_id){
        return $query->whereHas('channel.tube', function ($query) use ($tube_id){
            $query->where('id', $tube_id);
        });
    }

    public function scopeStore($query, User $user){
        return $query->whereHas('user', function ($query) use ($user){
            $query->store()->where('aid', $user->id);
        });
    }
    public function scopeMerchant($query, User $user){
        return $query->whereHas('user', function ($query) use ($user){
            $query->merchant()->where('aid', $user->id);
        });
    }


    public function isUnpay(){
        return intval($this->pay_status) === self::PAY_STATUS_UNPAY;
    }
    public function isPaid(){
        return intval($this->pay_status) === self::PAY_STATUS_PAID;
    }
    public function isFailed(){
        return intval($this->pay_status) === self::PAY_STATUS_FAILED;
    }
    public function isSettled(){
        return intval($this->is_settle) === self::IS_SETTLE_SETTLED;
    }
    public function waitingSettle(){
        return intval($this->is_settle) === self::IS_SETTLE_WAITING;
    }


    public function getTradeAmountAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }
    public function getRealAmountAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function channel() {
        return $this->belongsTo('App\Channel');
    }

    public final function custom_channel_id($search_item, $search_params)
    {
        $this->builder->whereHas('channel', function ($query) use ($search_item) {
            $query->where('id', $this->request->get($search_item));
        });
    }

    public final function custom_user_name($search_item, $search_params)
    {
        $this->builder->whereHas('user', function ($query) use ($search_item) {
            $query->where('name', 'like', '%' . $this->request->get($search_item) . '%');
        });
    }

    public static function getOrderByOrderNo($orderNo){
        return self::where('order_no', $orderNo)
            ->with('user')
            ->with('channel')
            ->first()
        ;
    }

    public static function getOrderByOutOrderNo($outOrderNo){
        return self::where('out_order_no', $outOrderNo)
            ->with('user')
            ->with('channel')
            ->first()
        ;
    }
}
