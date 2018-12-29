<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MerchantInfo extends Model
{
    //    ******软删除需求******
    use Searchable, SoftDeletes;
    protected $table='user_merchant_infos';

    protected $dates = ['delete_at'];
//    *******软删除需求******



    protected $fillable = [
        'user_id', 'wechat_merchant_no','merchant_address','ali_merchant_no',
        'ali_auth_token','company_name','business_person','email','identity_front'
        ,'identity_contrary','merchant_license','restaurant_license','registration_number',
        'identity_num','contract_tenancy','interior_picture','registrantname','mobile','status'
    ];

    const IN_AUDIT = 1;
    const SUCCESS_AUDIT = 2;
    const NOT_AUDIT = 3;
    const NEVER = 4;

    const STATUS =[
       self::IN_AUDIT => "审核中",
       self::SUCCESS_AUDIT => "审核通过",
       self::NOT_AUDIT => "审核未通过",
       self::NEVER => "还未进件",
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
