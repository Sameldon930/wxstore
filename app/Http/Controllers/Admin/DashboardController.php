<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Baidu\API;
use App\Libs\Baidu\BaiduAPI;
use App\Libs\Mrf\Mrf;
use App\Libs\Pay\Bill;
use App\Libs\Pay\Payment;
use App\Libs\Pay\PaymentException;
use App\Libs\Pay\Tubes\WechatPay;
use App\SettleLog;
use App\User;
use App\UserMerchantTube;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;
use App\Order as ModelOrder;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Payment\Client\Charge;
use Payment\Client\System;
use Payment\Common\PayException;
use Session;

class DashboardController extends Controller
{

    public function index(Request $request)
    {

        /*$wx2=User::with(['orders'=>function($query){
            $query->where('tube_id','=',1);
        }])->first();
        $wx2=$wx2->toArray();
        ndd($wx2);*/


        // 文字转语音
        /*if($request->get('voice')){
            $baidu = new BaiduAPI();

            return $baidu->generateVoice('我们都是好孩子');
        }*/

        // 计算分润
        /*if ($request->get('profit')) {
            SettleLog::profitCalculate();
        }

        // 生成结算单
        if ($request->get('settle')) {
            SettleLog::generateSettleLogs();
        }*/

        // 下载对账单
        if ($request->get('bill')) {
            $date = '20180626';

            // 下载对账单 整合微信和支付宝
            /*$bill = new Bill(['tube' => 'WECHAT', 'date' => $date]);
            $bill->run();
            dd(1);*/

            // 整理对账单数据 未完成
            /*$carbonStart = Carbon::parse($date);
            $carbonEnd = Carbon::parse($date)->endOfDay();
            $orders = ModelOrder
                ::whereBetween('created_at', [$carbonStart, $carbonEnd])
                ->paid()
                ->with('channel')
                ->get()
                ->groupBy('channel.tube_id')
            ;

            dump($orders->toArray());


            $csvData = [];
            if (($handle = fopen($date . '.csv', 'r')) !== false) {
                while (($data = fgetcsv($handle, 1000)) !== false) {
                    $csvData[] = $data;
                }
                fclose($handle);
            }

            $wechatOrders = array_slice($csvData, 1, -2);
            $wechatTotal = array_slice($csvData, -2);
            dump($csvData);
            dump($wechatOrders);
            dump($wechatTotal);*/


            //dump($orders->toArray());


            /*foreach ($orders as $orderKey => $order){
                foreach ($wechatOrders as $wechatOrderKey => $wechatOrder){
                    if (substr($wechatOrder[6], 1) === $order->order_no){
                        $order->wechat_order = $wechatOrder;
                        unset($wechatOrders[$wechatOrderKey]);
                    }
                }
            }
            dump($wechatOrders);
            dump($orders->toArray());*/
            dd(1);
        }
        return _view('admin.dashboard.index');
    }

}