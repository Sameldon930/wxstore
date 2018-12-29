<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaData extends Model
{
    protected $fillable = ['key', 'value', 'group'];

    public $timestamps = false;

    const MERCHANT_WECHAT_MAX_PROFIT_RATE = 'MERCHANT_WECHAT_MAX_PROFIT_RATE';
    const MERCHANT_ALI_MAX_PROFIT_RATE = 'MERCHANT_ALI_MAX_PROFIT_RATE';
    const VERSION_BACKEND = 'VERSION_BACKEND';
    const VERSION_FRONTEND = 'VERSION_FRONTEND';

    const META_DATA = [
        self::MERCHANT_WECHAT_MAX_PROFIT_RATE => '商户微信最高利润率',
        self::MERCHANT_ALI_MAX_PROFIT_RATE => '商户支付宝最高利润率',
        self::VERSION_BACKEND => '后台版本',
        self::VERSION_FRONTEND => '前端版本',
    ];

    public static function getValueByKey($key, $default = null){
        return self::where('key', $key)->value('value') ?: $default;
    }

    public static function getByKey($key){
        return self::where('key', $key)->first();
    }
}
