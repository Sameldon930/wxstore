<?php
namespace App\Http\Controllers\Api\In;

use App\Http\Controllers\Controller;
use App\Libs\Pay\Payment;
use App\Libs\Pay\PaymentException;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Payment\Client\System;


class PaymentController extends Controller
{
    /**
     * API总入口
     * @return [type] [description]
     */
    public function pay(Request $request)
    {
        if (isWechatClient()){
            return $this->wechatPrePay($request);
        } else if (isAliClient()){
            return $this->aliPrePay($request);
        }
    }

    private function wechatPrePay(Request $request){
        session_start();
        $user = $_SESSION[\App\Http\Controllers\PaymentController::SESSION_WECHAT_USER];
        $openid = $user['id'];

        $amount = $request->get('amount');
        $merchantNo = $request->get('u');

        $params = [
            'merchant_no' => $merchantNo,
            'channel' => 'WECHAT_JS',
            'amount' => $amount * 100,
        ];
        $specificParams = [
            'openid' => $openid,
        ];

        try {
            $payment = new Payment($params, $specificParams);
            $result = $payment->run();
        }catch (\Exception $e){
            throw new PaymentException($e->getMessage());
        }

        return $result;
    }

    private function aliPrePay(Request $request){
        $config = config('ali');
        $authCode = $request->get('auth_code');

        $amount = $request->get('amount');
        $merchantNo = $request->get('u');
        $buyerId = $this->getAliUserIdByAuthCode($authCode, $config);

        $params = [
            'merchant_no' => $merchantNo,
            'channel' => 'ALI_JS',
            'amount' => $amount * 100,
            'body' => config('app.name') . '商品',
        ];
        $specificParams = [
            'buyer_id' => $buyerId,
        ];

        try {
            $payment = new Payment($params, $specificParams);
            $result = $payment->run();
        }catch (\Exception $e){
            throw new PaymentException($e->getMessage());
        }

        return $result;
    }

    private function getAliUserIdByAuthCode($authCode, $baseConfig){
        $result = System::run('ali_oauth_token', $baseConfig, ['code' => $authCode,]);

        return $result['user_id'] ?? false;
    }
}
