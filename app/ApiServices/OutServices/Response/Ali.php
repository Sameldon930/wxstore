<?php
namespace App\ApiServices\OutServices\Response;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 14:57
 */
use App\User;
use Validator;
use App\Order;
use App\Libs\Pay\Payment;
use App\Libs\Pay\PaymentException;
use Illuminate\Support\Facades\Log;

class Ali
{
    protected $method = 'Ali';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        //初步校验
        $rules = [
            'amount' => 'required|numeric|max:500000',
            'subject' => 'required',
            'notify_url' => 'required',
            'out_order_no' => 'required|min:10|max:32',
        ];
        $message = [
            'amount.required' => '缺少amount',
            'amount.numeric' => 'amount必须为数字',
            'amount.max' => 'amount不能超过500000',
            'subject.required' => '缺少subject',
            'status.required' => '缺少status',
            'notify_url.required' => '缺少notify_url',
            'out_order_no.required' => '缺少out_trade_no',
            'out_order_no.min' => 'out_trade_no最短10个字符',
            'out_order_no.max' => '缺少out_trade_no最长32个字符',
        ];

        $validator = Validator::make($params, $rules, $message);

        if ($validator->fails()) {
            return [
                'status' => false,
                'code' => '2001',
                'msg' => $validator->errors()->all()
            ];
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
        $self_trade_no = formatNumber($params['user_id']) . date('YmdHis') . rand(1000, 9999);

        $user = User::getPayableMerchantUserByMobile($params['merchant_no']);

        //是否进件判断
        if (empty($user->user_merchant_info) || $user->user_merchant_info->ali_merchant_no == '' || $user->user_merchant_info->ali_auth_token == '') {
            return [
                'status' => false,
                'code' => '200123',
                'msg' => '暂未进件！'
            ];
        }
        //$channel = Channel::getChannelByName($params['pay_type']);
        $amount = $params['amount'];
        $db_result = Order::create([
            'user_id' => $user->id,
            'order_no' => $self_trade_no,
            'merchant_out_order_no' => $params['out_order_no'],
            'notify_url' => $params['notify_url'],
            'channel_id' => $params['channel_id'],
            'trade_amount' => $amount,
            'real_amount' => $amount,
            'status' => Order::STATUS_1,
            'pay_status' => Order::PAY_STATUS_UNPAY,
            'body' => $params['subject'],
        ]);

        if ($db_result) {
            $params = [
                'merchant_no' => $params['merchant_no'],
                'channel' => $params['pay_type'],
                'amount' => $amount,
            ];
            $specificParams = [];

            try {
                $payment = new Payment($params, $specificParams);
                $result = $payment->run($db_result);
            } catch (\Exception $e) {
                //throw new PaymentException($e->getMessage());
                Log::info('微信扫码请求错误：' . $e->getMessage());
                $result = [
                    'status' => false,
                    'code' => '200122',
                    'msg' => '业务错误！',
                ];
            }

            if ($result['status'] == true) {
                return [
                    'status' => true,
                    'code' => '200',
                    'data' => $result['code_url'],
                    'msg' => $result['msg']??'请求成功'
                ];
            }

            return [
                'status' => false,
                'code' => '200122',
                'msg' => $result['msg']
            ];
        } else {
            return [
                'status' => false,
                'code' => '200121',
                'msg' => '错误了'
            ];
        }
    }

}