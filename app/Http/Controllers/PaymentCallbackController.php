<?php

namespace App\Http\Controllers;

use App\ApiLog;
use App\ApiServices\ApiCommon;
use App\Libs\CloudPay\CloudPay;
use App\Libs\Pay\Lib\AliPayNotify;
use App\Order;
use App\Withdrawal;
use Carbon\Carbon;
use EasyWeChat\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Payment\Client\Notify;

class PaymentCallbackController extends Controller
{
    use ApiCommon;

    // 微信支付回调
    public function wechatPayCallback(Request $request)
    {
        $app = new Application(config('wechat'));

        $response = $app->payment->handleNotify(function ($data, $successful) {
            Log::info('微信订单通知内容：' . json_encode($data));
            $order = Order::getOrderByOrderNo($data->out_trade_no);

            if ($order && $successful) {
                if ($order->pay_status === Order::PAY_STATUS_PAID) {
                    Log::info('微信：订单重复回调-' . $data->out_trade_no);
                } else {
                    //TODO 订单快照内容不行，应该加入费率信息
                    $order->out_order_no = $data->transaction_id;
                    $order->paid_at = Carbon::createFromFormat('YmdHis', $data->time_end);
                    $order->response = json_encode($data);
                    $order->snap = json_encode($order->toArray());
                    $order->pay_status = Order::PAY_STATUS_PAID;
                    $order->save();

                    //时时通知处理 start
                    try {

                        $money = fenToYuan($order->trade_amount);
                        $name = 'name' . $order->user_id;
                        $url = 'http://111.230.183.134:9501/?money=' . $money . '&name=' . $name . '&type=1';
                        $this->cUrl($url);
                    }catch(\Exception $e){
                        Log::error($e->getMessage());
                    }

                    try {
                        $this->wx_template_message($order);
                    }catch(\Exception $e){
                        Log::error($e->getMessage());
                    }

                }
                if ($order->channel->name == 'WECHAT_SCAN'||$order->channel->name == 'WECHAT_H5') {
                    //执行异步通知内容
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
                Log::error('微信：找不到平台订单-' . $data->out_trade_no);
                return false;
            }
        });

        return $response;

    }

    // 支付宝支付回调
    public function aliPayCallback()
    {
        return Notify::run('ali_charge', config('ali'), new AliPayNotify());
    }

    // 云派支付回调
    public function cloudpayCallback(Request $request){
        $cloud_pay_responses = $request->all();

        ApiLog::create([
                'user_id' => 0,
                'note' => '云付回调数据',
                'request' =>'',
                'respond' => json_encode($cloud_pay_responses)]
        );

        $withdraw = Withdrawal::where('out_trade_no',$cloud_pay_responses['out_req_sn'])->first();
        if(empty($withdraw)){
            return false;
        }
        $cloud_pay = new CloudPay();

        $check_result = $cloud_pay->checkCallback($cloud_pay_responses);

        if ($check_result){
            if (intval($withdraw->status) === 1){
                return false;
            }else {
                $withdraw->status = Withdrawal::STATUS_FINISHED;
                $withdraw->save();
            }
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

    public function wx_template_message(Order &$order){
        //发送通知
        $openid = $order->user->wx_openid;
        if($openid == NULL){
            return false;
        }

        $app = new Application(config('wechat'));
        $notice = $app->notice;

        // $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        //交易通知
        $messageId = $notice->send([
            'touser' => $openid,
            'template_id' => 'owiJnmvSBIKhhZidcmQiiupkwiHhrZr05ZJs1sZ4LLk',
            'url' => 'http://mrf.huanhe.pro/store/store_query.html',
            'data' => [
                'first'=>'尊敬的 '. $order->user->name .' 店长，有一笔订单收款成功',
                'keyword1'=>'￥'.fenToYuan($order->trade_amount),
                'keyword2'=>   $order->channel->tube->display. '支付',
                'keyword3'=>    $order->order_no,
                'keyword4'=> date('Y-m-d H:i:s',time()),
                'remark'=>'详情请前往门店账户余额查看，祝您生意兴隆！',
            ],
        ]);
    }
}