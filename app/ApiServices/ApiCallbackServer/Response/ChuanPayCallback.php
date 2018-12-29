<?php
namespace App\Services\ApiCallbackServer\Response;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 14:57
 */


use App\Channel;
use App\Lib\ChuanPay;
use App\Order;
use App\ExternalApiLog;
use App\Services\ApiCallbackServer\OrderCallbackCommon;
use App\User;
use App\UserChannel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Request;

class ChuanPayCallback extends OrderCallbackCommon
{
    protected $method = 'ChuanPayCallback';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        return $this->run($params);
    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        $Pay = new ChuanPay();
        //验签成功

        if($result=$Pay->callback()){

            ExternalApiLog::create([
                    'user_id' => 0,
                    'note' => '创支付支付回调',
                    'request_data' => '',
                    'respond_data' => json_encode($params)]
            );
            $result = $params;

            //响应码错误
            $out_trade_no = $result['trade'];
            //dd($out_trade_no);
            $order_data = Order::where('self_trade_no', '=', $out_trade_no)->first();
            if (!$order_data) { // 如果订单不存在
                return 'Order not exist.';
            }

            $return_msg = [
                'status' => true,
                'code' => '200',
                'msg' => $Pay->respond_string, //msg是给上游的异步通知回应
                'order_id' => $order_data->id,
            ];

            if ($order_data->pay_status) {
                return $return_msg;
            }

            if ($result['trade_status'] == 'TRADE_SUCCESS') {
                DB::beginTransaction();
                $order_data->pay_status = 1;

                $order_snap = json_decode($order_data->order_snap, true);

                //计算上游手续费
                $order_data->upstream_server_charge = ceil($order_data->trade_amount * $order_snap['tube_rate'] / 100);

                //$user_channel_data = UserChannel::where('user_id', '=', $order_data->user_id)->where('channel_id', '=', $order_data->channel_id)->first();
                $downstream_server_charge = ceil(($order_data->trade_amount * $order_snap['channel_rate']) / 100);

                $order_data->downstream_server_charge = $downstream_server_charge;

                $order_data->pay_at = Carbon::now();
                $order_data->save();

                if (User::find($order_data->user_id)->settle_model == User::USER_SETTLE_BY_ORDER) {
                    $result = $this->dealSingleOrder($order_data);
                }
                DB::commit();
            }
            return $return_msg;

        };
        return false;

    }
}