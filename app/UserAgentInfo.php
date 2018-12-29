<?php

namespace App;

use App\Http\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAgentInfo extends Model
{
    use Searchable, SoftDeletes;

    protected $fillable = [
        'user_id', 'type', 'status',
        'legal_name', 'legal_idcard', 'legal_idcard_front', 'legal_idcard_back',
        'company_name', 'company_business_licence', 'company_account',
        'manager_name', 'manager_mobile',
        'cleaner_name', 'cleaner_mobile', 'cleaner_deposit', 'cleaner_idcard', 'cleaner_idcard_front', 'cleaner_idcard_back',
    ];

    const COLUMNS_MAP = [
        'type' => '账户类型',

        'company_name' => '公司名称',
        'company_account' => '公司对公账户',
        'company_business_licence' => '公司营业执照',

        'legal_name' => '法人姓名',
        'legal_idcard' => '法人身份证号',
        'legal_idcard_front' => '法人身份证正面',
        'legal_idcard_back' => '法人身份证反面',

        'manager_name' => '负责人姓名',
        'manager_mobile' => '负责人手机号',

        'cleaner_name' => '结算人姓名',
        'cleaner_mobile' => '结算人手机号',
        'cleaner_deposit' => '结算卡',
        'cleaner_idcard' => '结算人身份证号',
        'cleaner_idcard_front' => '结算人身份证正面',
        'cleaner_idcard_back' => '结算人身份证反面',
    ];

    const IMGS = [
        self::TYPE_PERSONAL => ['cleaner_idcard_front', 'cleaner_idcard_back'],
        self::TYPE_INDIVIDUAL_BUSINESS => [
            'company_business_licence',
            'legal_idcard_front', 'legal_idcard_back',
            'cleaner_idcard_front', 'cleaner_idcard_back',
        ],
        self::TYPE_COMPANY => [
            'company_business_licence',
            'legal_idcard_front', 'legal_idcard_back',
            'cleaner_idcard_front', 'cleaner_idcard_back',
        ],
    ];

    // showFormByType 也需要修改
    const TYPE_PERSONAL = 1;
    const TYPE_INDIVIDUAL_BUSINESS = 2;
    const TYPE_COMPANY = 3;
    const TYPES = [
        self::TYPE_PERSONAL => '个人',
        self::TYPE_INDIVIDUAL_BUSINESS => '个体工商户',
        self::TYPE_COMPANY => '企业',
    ];

    const CHECKING = 1;
    const CHECKED = 2;
    const REJECTED = 3;
//    const bykk
    const CHECK_STATUS = [
        self::CHECKING => '审核中',
        self::CHECKED => '审核通过',
        self::REJECTED => '已拒绝',
    ];

    public function scopeChecking($query){
        return $query->where('status', self::CHECKING);
    }
    public function scopeChecked($query){
        return $query->where('status', self::CHECKED);
    }
    public function scopeRejected($query){
        return $query->where('status', self::REJECTED);
    }

    public function scopeUnchecked($query){
        return $query->whereIn('status', [self::CHECKING, self::REJECTED]);
    }

    public function isChecking(){
        return $this->status === self::CHECKING;
    }
    public function isChecked(){
        return $this->status === self::CHECKED;
    }
    public function isRejected(){
        return $this->status === self::REJECTED;
    }
    public function isUnchecked(){
        return in_array($this->status, [self::CHECKING, self::REJECTED]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getInfoCheckMessages(){
        $messages = [];

        $messages = $messages + [
            'company_name.required' => '公司名称必须填写',
            'company_account.required' => '公司对公账户必须填写',
            'company_business_licence.required' => '必须上传公司营业执照图片',

            'legal_name.required' => '法人姓名必须填写',
            'legal_idcard.required' => '法人身份证号必须填写',
            'legal_idcard_front.required' => '必须上传法人身份证正面图片',
            'legal_idcard_back.required' => '必须上传法人身份证反面图片',

            'manager_name.required' => '负责人姓名必须填写',
            'manager_mobile.required' => '负责人手机号必须填写',

            'cleaner_name.required' => '结算人姓名必须填写',
            'cleaner_mobile.required' => '结算人手机号必须填写',
            'cleaner_deposit.required' => '结算卡必须填写',
            'cleaner_idcard.required' => '结算人身份证号必须填写',
            'cleaner_idcard_front.required' => '必须上传结算人身份证正面图片',
            'cleaner_idcard_back.required' => '必须上传结算人身份证反面图片',


            'company_name.regex' => '公司名称必须为中文',
            'company_account.regex' => '公司对公账户格式不正确',

            'legal_name.regex' => '法人姓名必须为中文',
            'legal_idcard.regex' => '法人身份证号格式不正确',

            'manager_name.regex' => '负责人姓名必须为中文',
            'manager_mobile.regex' => '负责人手机号必须为11位数字',

            'cleaner_name.regex' => '结算人姓名必须为中文',
            'cleaner_mobile.regex' => '结算人手机号必须为11位数字',
            'cleaner_deposit.regex' => '结算卡格式不正确',
            'cleaner_idcard.regex' => '结算人身份证号格式不正确',
        ];

        return $messages;
    }
}
