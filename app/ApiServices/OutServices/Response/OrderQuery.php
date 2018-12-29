<?php
namespace App\ApiServices\OutServices\Response;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 14:57
 */
use Validator;
use App\Order;
use App\User;

class OrderQuery
{
    protected $method = 'OrderQuery';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        //初步校验
        $rules = [
            'out_order_no' => 'required',
        ];
        $message = [
            'out_order_no.required' => '缺少out_trade_no',
        ];

        $validator = Validator::make($params, $rules, $message);

        if ($validator->fails()) {
            return ['status' => false, 'code' => '2001', 'msg' => $validator->errors()->all()];
        } else {
            return $this->run($params);
        }
    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        $user_data = User::where('mobile', '=', $params['merchant_no'])->first();
        $order_data = Order::where('merchant_out_order_no', '=', $params['out_order_no'])->where('user_id', '=', $user_data->id)->first();
        if (!empty($order_data)) {
            if ($order_data->pay_status == Order::PAY_STATUS_PAID) {
                return [
                    'status' => true,
                    'code' => '200',
                    'data' => ['out_order_no' => $params['out_order_no'], 'amount' => $order_data->real_amount, 'pay_status' => 'paySuccess', 'paid_at' => $order_data->paid_at]
                ];
            } else {
                return [
                    'status' => true,
                    'code' => '200',
                    'data' => ['out_order_no' => $params['out_order_no'], 'pay_status' => 'notPay']
                ];
            }
        } else {
            return [
                'status' => true,
                'code' => '200',
                'data' => ['out_order_no' => $params['out_order_no'], 'pay_status' => 'errorOrder']
            ];
        }
    }

}