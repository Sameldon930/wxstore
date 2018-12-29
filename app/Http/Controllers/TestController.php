<?php

namespace App\Http\Controllers;



use App\ApiServices\ApiCommon;
use App\Libs\Baidu\BaiduAPI;
use App\Libs\Pay\Lib\AliPayNotify;
use App\Libs\Pay\Payment;
use App\Order;
use App\SettleLog;
use App\User;
use Carbon\Carbon;

use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class TestController
{
    use ApiCommon;
    public function index(){
        die;
        $aa = new PaymentCallbackController();
        $order = Order::find(155);
        $aa ->wx_template_message($order);
        //session_start();
        //$openid = $_SESSION[\App\Http\Controllers\PaymentController::SESSION_WECHAT_USER];
        //dd($openid);die;

        die;
        //发送通知
        $openid="oHkld1vZkC6eAgLSohItbFaYQiiE";
        $app = new Application(config('wechat'));
        $notice = $app->notice;

       // $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        //交易通知
        $messageId = $notice->send([
            'touser' => $openid,
            'template_id' => 'owiJnmvSBIKhhZidcmQiiupkwiHhrZr05ZJs1sZ4LLk',
            'url' => ' ',
            'data' => [
               'first'=>'尊敬的 喵喵姐（金湖店）店长，有一笔订单收款成功',
               'keyword1'=>'￥20.00',
               'keyword2'=>'微信支付',
               'keyword3'=>'12345678900',
               'keyword4'=>'2017-01-01 12:12:12',
               'remark'=>'详情请前往门店账户余额查看，祝您生意兴隆！',
            ],
        ]);
        die;
/*
        array:8 [▼
  "id" => "oHkld1vZkC6eAgLSohItbFaYQiiE"
  "name" => null
  "nickname" => null
  "avatar" => null
  "email" => null
  "original" => array:5 [▼
    "access_token" => "15_GOVM1mXTJ1AUlKARL1FTC1x99SA7ccHBi9Rk9YGhWSIo2UdKBx54_ggvKCa2Voly7JUpAoU2MJMCiDr5k9bWXCJjPfvfvrdJMSPsFjsD1rQ"
    "expires_in" => 7200
    "refresh_token" => "15_Mop095EHZORf0jSlIZAIkM771lvfHBNpm1alw_gyg1D-tatAIMwjYw-6BLq2M4xkEs8Fr3kZeSzYZ74fQCEfDeHARQp6X_n6IJMhk26e0WY"
    "openid" => "oHkld1vZkC6eAgLSohItbFaYQiiE"
    "scope" => "snsapi_base"
  ]
  "token" => AccessToken {#385 ▼
            #attributes: array:5 [▼
            "access_token" => "15_GOVM1mXTJ1AUlKARL1FTC1x99SA7ccHBi9Rk9YGhWSIo2UdKBx54_ggvKCa2Voly7JUpAoU2MJMCiDr5k9bWXCJjPfvfvrdJMSPsFjsD1rQ"
      "expires_in" => 7200
      "refresh_token" => "15_Mop095EHZORf0jSlIZAIkM771lvfHBNpm1alw_gyg1D-tatAIMwjYw-6BLq2M4xkEs8Fr3kZeSzYZ74fQCEfDeHARQp6X_n6IJMhk26e0WY"
      "openid" => "oHkld1vZkC6eAgLSohItbFaYQiiE"
      "scope" => "snsapi_base"
    ]
  }
  "provider" => "WeChat"
]*/

        die;
        $data1=[
            'merchant_no' => '10038',
            'channel' => 'WECHAT_H5',
            'amount' => 100,
        ];
        $data2=['spbill_create_ip'=>'120.36.255.25'];
        $a=new Payment($data1,$data2);
        //需要再$db_result 加入IP地址
        $result = $a->run($db_result);
        dd($result);
        //$a=new BaiduAPI();
        //dd($a->generateVoice('支付宝到账10.5元'));

        /*$usersChannelsOrders = SettleLog::CreateDate( Carbon::now()->subDays(5))->where('user_id','!=',0)->whereHas('tube', function ($query) {
            $query->where('id', 1);
        })->get()->groupBy(['user_id','tube_id']);*/
       // dd($usersChannelsOrders);

        die;
        $a=Order::paginate(20,null,null,5);
        dd($a->toArray());
        //SettleLog::generateSettleLogs();
        die;
        $aa=User::store()->where('aid','=',7)
            ->with(['orders'=>function($query){
                $query->select(DB::raw('user_id,sum(trade_amount)'))->groupBy('user_id');
            }])
            ->get();
        dd($aa->toArray());
        die;
        SettleLog::generateSettleLogs();
        die;

    }
}
