<?php

namespace App;

use App\Http\Traits\CommonExportTrait;
use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class AccountLog extends Model
{
    use Searchable ,CommonExportTrait;

    protected $fillable = ['order_id','user_id', 'business', 'no', 'amount', 'balance', 'type', 'flow', 'snap'];

    const BUSINESS_ORDER = 1;
    const BUSINESS_WITHDRAWAL = 2;
    const BUSINESS_SETTLE = 3;
    const BUSINESSES = [
        self::BUSINESS_ORDER => '交易账变',
        self::BUSINESS_WITHDRAWAL => '提现账变',
        self::BUSINESS_SETTLE => '结算账变',
    ];

    // 账变类型
    const MERCHANT_ACCOUNT_TYPES = [

    ];

    const AGENT_ACCOUNT_TYPES = [

    ];


    //const WITHDRAWAL_ACCOUNT_BALANCE = 'WITHDRAWAL_ACCOUNT_BALANCE';
    const WITHDRAWAL_ACCOUNT_BALANCE = '3';
    //const WITHDRAWAL_ACCOUNT_BALANCE_REJECTED = 'WITHDRAWAL_ACCOUNT_BALANCE_REJECTED';
    const WITHDRAWAL_ACCOUNT_BALANCE_REJECTED = '4';

    const WITHDRAWAL_TYPES = [
        self::WITHDRAWAL_ACCOUNT_BALANCE => '提现-账户余额提现',
        self::WITHDRAWAL_ACCOUNT_BALANCE_REJECTED => '提现-拒绝账户余额提现',
    ];


    //const SETTLE_MARCHANT_TRADE_PROFIT = 'SETTLE_MARCHANT_TRADE_PROFIT';
    const SETTLE_MERCHANT_TRADE_PROFIT = '1';
    //const SETTLE_TRADE_PROFIT = 'SETTLE_TRADE_PROFIT';

    const SETTLE_TRADE_PROFIT = '2';
    const SETTLE_TYPES = [
        self::SETTLE_MERCHANT_TRADE_PROFIT => '结算-子商户交易分润',
        self::SETTLE_TRADE_PROFIT => '结算-商户交易分润',
    ];

    const ACCOUNT_TYPES =
        self::MERCHANT_ACCOUNT_TYPES +
        self::AGENT_ACCOUNT_TYPES +
        self::SETTLE_TYPES +
        self::WITHDRAWAL_TYPES
    ;
    // end 账变类型

    const FLOW_IN = 1;
    const FLOW_OUT = 2;
    const FLOWS = [
        self::FLOW_IN => '转入',
        self::FLOW_OUT => '转出',
    ];

    public function getAmountAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }
    public function getBalanceAttribute($value){
        return app()->resolved('blade.compiler') ? fenToYuan($value) : $value;
    }

    public final function custom_mobile($search_item, $search_params)
    {
        $this->builder->whereHas('user', function ($query) use ($search_item) {
            $query->where('mobile', 'like', '%' . $this->request->get($search_item) . '%');
        });
    }

    public final function custom_tube_id($search_item, $search_params)
    {
        $this->builder->whereHas('settle_order', function ($query) use ($search_item) {
            $query->whereHas('tube', function ($query2) use ($search_item){
                $query2->where('id', '=', $this->request->get($search_item));
            });
        });
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function settle_order(){
        return $this->hasOne(SettleLog::class,'id','order_id');
    }
}
