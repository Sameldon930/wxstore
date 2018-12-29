<?php

namespace App\Libs\Pay;


use App\Channel;
use App\Libs\Pay\Tubes\AliPay;
use App\Libs\Pay\Tubes\WechatPay;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class Payment
{
    protected $params;
    protected $specificParams;
    protected $rules = [];
    protected $specificRules = [];

    protected $order;

    protected $payInstance; // 支付实例 Tubes下
    protected $payMethod; // 支付方法

    public function __construct($params, $specificParams = [])
    {
        $this->params = $params;
        $this->specificParams = $specificParams;
        $this->rules = [
            'merchant_no' => 'required|exists:users,mobile',
            'channel' => 'required|exists:channels,name',
            'amount' => 'required|integer',
            'body' => 'required',
        ];

        $this->init();

        $this->checkParams();
        $this->checkSpecificParams();
    }

    public function init(){
        switch ($this->params['channel']){
            //这里的case 参考数据表channels
            case 'WECHAT_SCAN':
                $this->payInstance = new WechatPay();
                $this->payMethod = 'scanPay';
                break;
            case 'WECHAT_BAR':
                $this->payInstance = new WechatPay();
                $this->payMethod = 'barPay';
                break;
            case 'WECHAT_JS':
                $this->payInstance = new WechatPay();
                $this->payMethod = 'jsPay';
                break;
            case 'WECHAT_H5':
                $this->payInstance = new WechatPay();
                $this->payMethod = 'h5Pay';
                break;

            case 'ALI_SCAN':
                $this->payInstance = new AliPay();
                $this->payMethod = 'scanPay';
                break;
            case 'ALI_BAR':
                $this->payInstance = new AliPay();
                $this->payMethod = 'barPay';
                break;
            case 'ALI_JS':
                $this->payInstance = new AliPay();
                $this->payMethod = 'jsPay';
                break;

            default:
                throw new PaymentException('不支持该渠道' . $this->params['channel']);
        }
    }

    public function run(&$order=null){
        if($order==null) {
            $this->createOrder();
        }else{
            $this->order = $order;
        }

        $method = $this->payMethod;

        return $this->payInstance->$method($this->order, $this->specificParams);
    }

    private function createOrder(){
        $user = User::getPayableMerchantUserByMobile($this->params['merchant_no']);
        $channel = Channel::getChannelByName($this->params['channel']);

        $order = Order::create([
            'user_id' => $user->id,
            'order_no' => $this->generateOrderNo($user->id),
            'channel_id' => $channel->id,
            'trade_amount' => $this->params['amount'],
            'real_amount' => $this->params['amount'],
            'status' => Order::STATUS_1,
            'pay_status' => Order::PAY_STATUS_UNPAY,
            'body' => $user->name,
        ]);

        // TODO 模拟下昨天的订单 方便结算
       /* if (env('APP_DEBUG')){
            $order->created_at = $order->created_at->subDay();
            $order->updated_at = $order->updated_at->subDay();
            $order->save();
        }*/

        $order->channel = $channel;
        $order->user = $user;

        $this->order = $order;
    }

    private function checkParams(){

        $rules = [
            'merchant_no' => 'required|exists:users,mobile',
            'channel' => 'required|exists:channels,name',
            'amount' => 'required|integer',
        ];

        $messages = [
            'merchant_no.required' => '缺少商户号',
            'merchant_no.exists' => '商户号不存在',
            'channel.required' => '缺少渠道标识',
            'channel.exists' => '渠道未开通',
            'amount.required' => '缺少交易金额',
            'amount.integer' => '交易金额必须为数字',
        ];

        $validator = Validator::make($this->params, $rules, $messages);

        if ($validator->fails()){
            throw new PaymentException($validator->errors()->first());
        }
    }

    //额外的验证规则
    private function checkSpecificParams(){
        $this->specificRules = $this->payInstance->specificRules[$this->payMethod];

        $validator = Validator::make($this->specificParams, $this->specificRules);

        if ($validator->fails()){
            throw new PaymentException($validator->errors()->first());
        }
    }

    private function generateOrderNo($user_id=null){
        if($user_id){
            return formatNumber($user_id) . date('YmdHis') . rand(1000, 9999);
        }
        return date('YmdHis') . random_int(1000, 9999);
    }

}