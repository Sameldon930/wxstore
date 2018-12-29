<?php

namespace App\Libs\Pay;


use App\Channel;
use App\Libs\Pay\Tubes\AliPay;
use App\Libs\Pay\Tubes\WechatPay;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Validator;

class Bill
{
    protected $params;
    protected $specificParams;
    protected $rules = [];
    protected $specificRules = [];

    protected $payInstance; // 支付实例 Tubes下
    protected $payMethod; // 支付方法

    public function __construct($params)
    {
        $this->params = $params;

        $this->init();

        $this->checkParams();
    }

    public function init(){
        switch ($this->params['tube']){
            case 'WECHAT':
                $this->payInstance = new WechatPay();
                $this->payMethod = 'downloadBill';
                break;

            case 'ALI':
                $this->payInstance = new AliPay();
                $this->payMethod = 'downloadBill';
                break;

            default:
                throw new PaymentException('不支持该通道' . $this->params['tube']);
        }
    }

    public function run(){
        $method = $this->payMethod;
        return $this->payInstance->$method($this->params);
    }


    private function checkParams(){
        $rules = [
            'tube' => 'required|exists:tubes,name',
            'date' => 'required',
            //'users' => 'required',
        ];

        $validator = Validator::make($this->params, $rules);

        if ($validator->fails()){
            throw new PaymentException($validator->errors()->first());
        }
    }
}