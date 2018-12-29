<?php

namespace App\Libs\Pay\Tubes;


use App\Exceptions\ErrorMessageException;
use App\Libs\Pay\PaymentException;
use App\Order;
use App\User;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Payment\Client\Charge;
use Payment\Client\System;
use Payment\Common\PayException;

class AliPay extends BasePay
{

    public $specificRules = [
        'scanPay' => [],
        'barPay' => ['auth_code' => 'required'],
        'jsPay' => ['buyer_id' => 'required'],
    ];

    public function __construct()
    {
        $this->config = config('ali');
        $this->config['partner'] = '2088702361851285';
        $this->config['app_auth_token'] = '201806BB2859d2cdc90142bd9a3e0a0789230F28';
    }

    private function initConfig(Order $order){

        $aliMerchantNo = $order->user->payable_user->user_merchant_info->ali_merchant_no ?? false;
        $aliAuthToken = $order->user->payable_user->user_merchant_info->ali_auth_token ?? false;

        if (!$aliMerchantNo || !$aliAuthToken){
            throw new PaymentException('商户未认证或配置错误，请联系客服。');
        }

        $this->config['partner'] = $aliMerchantNo;
        $this->config['app_auth_token'] = $aliAuthToken;
    }

    public function scanPay(Order $order, $specificParams){
        // 扫码支付
        $payConfig = [
            'subject' => $order->body,
            'order_no' => $order->order_no,
            'amount' => $order->real_amount / 100,
        ];

        $result = Charge::run('ali_qr', $this->config, $payConfig);

        return $this->response(__FUNCTION__, $result);
    }

    public function barPay(Order $order, $specificParams){
        $payConfig = [
            'subject' => $order->body,
            'order_no' => $order->order_no,
            'amount' => $order->real_amount / 100,
            'scene' => 'bar_code',
            'auth_code' => $specificParams['auth_code']
        ];

        $result = Charge::run('ali_bar', $this->config, $payConfig);

        return $this->response(__FUNCTION__, $result);
    }

    public function jsPay(Order $order, $specificParams){
        $this->initConfig($order);

        $payConfig = [
            'subject' => $order->order_no,
            'order_no' => $order->order_no,
            'amount' => $order->real_amount / 100,
            'buyer_id' => $specificParams['buyer_id'],
        ];

        $result = Charge::run('ali_js', $this->config, $payConfig);

        return $this->response(__FUNCTION__, $result);
    }

    private function response($method, $result){
        if ($result['code'] == '10000'){
            switch ($method){
                case 'scanPay':
                    $response = [
                        'code_url' => $result['qr_code'],
                    ];
                    break;
                case 'barPay':
                    $response = [

                    ];
                    break;
                case 'jsPay':
                    $response = [
                        'trade_no' => $result['trade_no'],
                    ];
                    break;
            }
            $response['status'] = true;
            $response['code'] = 'SUCCESS';
            return $response;

        }else {
            Log::info('未知错误');
            Log::info($result);
            return ['code' => 'FAIL', 'msg' => $result['sub_msg'] ?? '未知错误','status'=>false];
        }
    }


    public function downloadBill($params){
        $storage = Storage::disk('bill');
        $date = $params['date'];
        $tube = $params['tube'];
        $users = $params['users'];

        $dirName = $date . '-' . $tube;
        $extractedDirName = $dirName . '/' . 'extracted';
        $responseFileName = $storage->path($dirName . '.zip');

        $mataData = [
            'bill_type' => 'trade',
            'bill_date' => $date,
        ];

        if ($users->count()){
            foreach ($users as $user_id){
                $user = User::find($user_id);
                $fileName = $dirName . '/' . $user->payable_user->user_merchant_info->ali_merchant_no . '.zip';

                // 接口返回下载地址
                $response = System::run('ali_download_bill', $this->config, $mataData);

                if ($response['code'] != '10000'){
                    Log::error('支付宝对账单下载错误');
                    Log::error($response);
                    throw new ErrorMessageException("支付宝对账单下载错误{$fileName}，请联系管理员");
                }

                // 文件下载到本地
                $result = $storage->put($fileName, file_get_contents($response['bill_download_url']));

                if (!$result){
                    throw new ErrorMessageException("文件存储失败（{$fileName}），请联系管理员");
                }

                // 解压到 extracted 目录
                Zipper::make($storage->path($fileName))->extractTo($storage->path($extractedDirName));
            }

            // 将 extracted 目录下文件添加到压缩包
            $files = glob($storage->path($extractedDirName));
            Zipper::make($responseFileName)->add($files)->close();

            // 删除 extracted 目录
            $storage->deleteDirectory($extractedDirName);

            return $responseFileName;
        }else {
            throw new ErrorMessageException('无交易订单');
        }
    }







    private function tryCatch(){
        try {

        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }
}