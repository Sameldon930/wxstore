<?php

namespace App\Libs\Pay\Tubes;


use App\Exceptions\ErrorMessageException;
use App\Libs\Pay\PaymentException;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use App\Order as ModelOrder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WechatPay extends BasePay
{

    // Easy Wechat 支付实例
    protected $payment;
    protected $app;
    protected $config;

    //这里是对具体渠道的更详细的要求
    public $specificRules = [
        'scanPay' => [],
        'barPay' => ['auth_code' => 'required'],
        'jsPay' => [],
        'h5Pay' => ['spbill_create_ip'=>'required|ip'],
    ];

    public function __construct()
    {
        $config = config('wechat');
        $this->config = $config;
    }

    public function initBasePayment()
    {
        $this->app = new Application($this->config);
        $this->payment = $this->app->payment;
    }

    private function initPayment(ModelOrder $order){

        $wechantMerchantNo = $order->user->payable_user->user_merchant_info->wechat_merchant_no ?? false;

        if (!$wechantMerchantNo){
            throw new PaymentException('商户未认证或配置不正确，请联系客服。');
        }
        $this->config['payment']['sub_merchant_id'] = $wechantMerchantNo;
        $this->app = new Application($this->config);

        $this->payment = $this->app->payment;

        // $this->payment->sandboxMode(true);
    }


    public function scanPay(ModelOrder $modelOrder, $specificParams){
        $this->initPayment($modelOrder);

        $attributes = [
            'body'             => $modelOrder->body,
            'out_trade_no'     => $modelOrder->order_no,
            'total_fee'        => $modelOrder->real_amount, // 单位：分
            'trade_type'        => 'NATIVE',
        ];

        if (env('APP_DEBUG')){
            $attributes['spbill_create_ip'] = '127.0.0.1';
        }
        //dd($attributes);
        $order = new Order($attributes);
        $result = $this->payment->prepare($order);

        return $this->response(__FUNCTION__, $result);
    }

    public function barPay(ModelOrder $modelOrder, $specificParams){
        $attributes = [
            'body'             => $modelOrder->body,
            'out_trade_no'     => $modelOrder->order_no,
            'total_fee'        => $modelOrder->real_amount, // 单位：分
            'auth_code'        => $specificParams['auth_code'],
        ];

        $order = new Order($attributes);
        $result = $this->payment->pay($order);

        return $this->response(__FUNCTION__, $result);
    }

    public function jsPay(ModelOrder $modelOrder, $specificParams){
        $this->initPayment($modelOrder);

        $attributes = [
            'trade_type'       => 'JSAPI',
            'body'             => $modelOrder->body,
            'detail'           => $modelOrder->body,
            'out_trade_no'     => $modelOrder->order_no,
            'total_fee'        => $modelOrder->real_amount, // 单位：分
            'openid'           => $specificParams['openid'], // 如果传入sub_openid, 请在实例化Application时, 同时传入$sub_app_id, $sub_merchant_id
            'spbill_create_ip' => '127.0.0.1',
        ];

        $order = new Order($attributes);
        $result = $this->payment->prepare($order);

        if (isset($result->prepay_id)){
            $paymentParams = $this->payment->configForPayment($result->prepay_id);
            $result->paymentParams = $paymentParams;
        }

        return $this->response(__FUNCTION__, $result);
    }

    public function h5Pay(ModelOrder $modelOrder, $specificParams){
        $this->initPayment($modelOrder);

        $attributes = [
            'trade_type'       => 'MWEB',
            'body'             => $modelOrder->body,
            'detail'           => $modelOrder->body,
            'out_trade_no'     => $modelOrder->order_no,
            'total_fee'        => $modelOrder->real_amount, // 单位：分
            'openid'           => '', // 如果传入sub_openid, 请在实例化Application时, 同时传入$sub_app_id, $sub_merchant_id
            'spbill_create_ip' => $specificParams['spbill_create_ip'],
        ];

        $order = new Order($attributes);
        $result = $this->payment->prepare($order);
        /*$result 返回结果
         * "return_code" => "SUCCESS"
    "return_msg" => "OK"
    "appid" => "wx71b3c3e3ce5907f8"
    "mch_id" => "1503678491"
    "sub_mch_id" => "1514571551"
    "nonce_str" => "6yNB9t22fYywNyoG"
    "sign" => "49A37E727715EE7AF2432347ECB7539F"
    "result_code" => "SUCCESS"
    "prepay_id" => "wx1817083492527924176494e91488094578"
    "trade_type" => "MWEB"
    "mweb_url" => "https://wx.tenpay.com/cgi-bin/mmpayweb-bin/checkmweb?prepay_id=wx1817083492527924176494e91488094578&package=2496766937"*/
        if (isset($result->prepay_id)){
            $paymentParams = $this->payment->configForPayment($result->prepay_id);
            $result->paymentParams = $paymentParams;
        }

        return $this->response(__FUNCTION__, $result);
    }

    private function response($method, $result){
        if ($result->return_code == 'FAIL'){
            return ['code' => 'FAIL', 'msg' => $result->return_msg,'status'=>false];
        }else if ($result->return_code == 'SUCCESS' && $result->result_code == 'FAIL'){
            return ['code' => 'FAIL', 'msg' => $result->err_code_des,'status'=>false];
        }else if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){

            switch ($method){
                case 'scanPay':
                    $response = [
                        'code_url' => $result->code_url,
                    ];
                    break;
                case 'barPay':
                    $response = [

                    ];
                    break;
                case 'jsPay':
                    $response = [
                        'prepay_id' => $result->prepay_id,
                        'paymentParams' => $result->paymentParams,
                    ];
                    break;
                case 'h5Pay':
                    $response = [
                        'prepay_id' => $result->prepay_id,
                        'code_url' => $result->mweb_url,
                    ];
                    break;
            }
            $response['status'] = true;
            $response['code'] = 'SUCCESS';
            return $response;
        }else {
            Log::info('未知错误');
            Log::info($result);
            return ['code' => 'FAIL', 'msg' => '未知错误','status'=>false];
        }
    }

    public function downloadBill($params){

        $this->initBasePayment();

        $storage = Storage::disk('bill');
        $date = $params['date'];
        $tube = $params['tube'];
        $fileName = $date . '-' . $tube . '.csv';

        $billContent = $this->payment->downloadBill(str_replace('-', '', $date))->getContents();
        // $bill = $this->payment->downloadBill('20140603', 'SUCCESS')->getContents();

        $result = $storage->put($fileName, $billContent);

        if ($result){
            return $storage->path($fileName);
        }else {
            throw new ErrorMessageException('文件存储失败，请联系管理员');
        }
    }
}