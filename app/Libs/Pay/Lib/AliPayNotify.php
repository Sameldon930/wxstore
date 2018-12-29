<?php

namespace App\Libs\Pay\Lib;

use App\ApiServices\ApiCommon;
use App\Http\Controllers\PaymentCallbackController;
use App\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Payment\Notify\PayNotifyInterface;

class AliPayNotify implements PayNotifyInterface
{
    use ApiCommon;

    public function notifyProcess(array $data)
    {
        Log::info('支付宝异步通知：' . json_encode($data));

        $order = Order::getOrderByOrderNo($data['out_trade_no']);
        if ($order) {

            if ($order->pay_status === Order::PAY_STATUS_PAID) {
                Log::info('支付宝：订单重复回调-' . $data['out_trade_no']);
            } else {
                $order->out_order_no = $data['trade_no'];
                $order->pay_status = Order::PAY_STATUS_PAID;
                $order->paid_at = Carbon::createFromFormat('Y-m-d H:i:s', $data['gmt_payment']);
                $order->response = json_encode($data);
                $order->snap = json_encode($order->toArray());
                $order->save();

                try {
                    $money = fenToYuan($order->trade_amount);
                    $name = 'name' . $order->user_id;
                    $url = 'http://111.230.183.134:9501/?money=' . $money . '&name=' . $name . '&type=2';
                    $this->cUrl($url);
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }

                try {
                    $call_back=new PaymentCallbackController();
                    $call_back->wx_template_message($order);
                }catch(\Exception $e){
                    Log::error($e->getMessage());
                }

            }
            if ($order->channel->name == 'ALI_SCAN') {
                //执行异步同时内容
                $result = $this->callbackResponse($this->response([
                    'status' => true,
                    'code' => '200',
                    'msg' => 'success', //msg是给上游的异步通知回应
                    'order_id' => $order->id,
                ]));
            }


            if (isset($result) && $result == 'success') {
                return true;
            }
            return false;
        } else {
            Log::error('支付宝：找不到平台订单-' . $data['out_trade_no']);
            return false;
        }

    }

    public function cUrl($url, $header = null, $data = null)
    {
        //初始化curl
        $curl = curl_init();
        //设置cURL传输选项

        if (is_array($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);

        if (!empty($data)) {//post方式
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //获取采集结果
        $output = curl_exec($curl);

        //关闭cURL链接
        curl_close($curl);

        return $output;
    }


}