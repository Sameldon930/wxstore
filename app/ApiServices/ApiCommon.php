<?php
namespace App\ApiServices;

use App\User;
use App\UserAgentChannel;
use Validator;
use App\Jobs\SendCallbackMsg;
use App\Order;
use App\Withdrawal;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/14
 * Time: 11:41
 */
trait ApiCommon
{
    /**
     * 是否输出错误码
     * @var boolean
     */
    protected $error_code_show = false;

    /**
     * 回调数据格式
     * @var string
     */
    protected $format = 'json';

    /**
     * 秘钥
     * @var array
     */
    protected $key = '';


    /**
     * 签名方法
     * @var string
     */
    protected $sign_method = 'MD5';

    /**
     * 商户用户数据
     * @var object
     */
    protected $merchant_data = '';


    /**
     * 初步校验，校验必要参数
     * @return array
     */
    protected function checkCommonParams()
    {
        //return ['status' => false, 'code' =>'600']; //系统升级返回
        // 1初步校验  错误码再跳转
        $rules = [
            'version' => 'required|regex:/1.0/',
            'sign_type' => 'required|regex:/MD5/',
            'merchant_no' => 'required|string|min:5|max:20|',
            'sign' => 'required',
        ];
        $messages = [
            'sign.required' => '1001',
            'merchant_no.required' => '1003',
            'merchant_no.string' => '1004',
            'merchant_no.min' => '1005',
        ];

        $v = Validator::make($this->params, $rules, $messages);

        if ($v->fails()) {
            return ['status' => false, 'code' => $v->messages()->first()];
        }

        //获取key值
        $this->key = $this->getMerchantKey($this->params);
        if (!$this->key) {
            return ['status' => false, 'code' => '1007'];
        }

        // 2校验签名
        $signRes = $this->checkSign($this->params, $this->key, $this->sign_method);
        if (!$signRes || !$signRes['status']) {
            return ['status' => false, 'code' => $signRes['code']];
        }

        return array('status' => true, 'code' => '200');
    }

    /**
     * 校验签名
     * @param  array $params 待校验签名参数
     * @param  string $key 加密秘钥
     * @param  string $sign_method 加密方式
     * @return array
     */
    protected function checkSign($params, $key, $sign_method)
    {
        $sign = array_key_exists('sign', $params) ? $params['sign'] : '';

        if (empty($sign))
            return array('status' => false, 'code' => '1001');

        unset($params['sign']);

        //echo $this->generateSign($params,$key,$sign_method);die;
        if ($sign != $this->generateSign($params, $key, $sign_method)) {
            return array('status' => false, 'code' => '1002');
        }

        $this->params['user_id'] = $this->user_id;
        return array('status' => true, 'code' => '200');
    }

    /**
     * 生成签名
     * @param  array $params 待校验签名参数
     * @param  string $key 加密秘钥
     * @param  string $sign_method 加密方式
     * @return string|false
     */
    protected function generateSign($params, $key, $sign_method = 'MD5')
    {

        if ($sign_method == 'MD5')
            return $this->generateMd5Sign($params, $key);

        return false;
    }

    /**
     * md5方式签名
     * @param  array $params 待签名参数
     * @param  array $key 加密秘钥
     * @return string
     */
    public function generateMd5Sign($params, $key)
    {
        $sign_str = '';
        // 排序
        ksort($params);

        $buff = "";
        foreach ($params as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        $sign2 = strtoupper(md5($buff . '&key=' . $key));

        return $sign2;
    }

    /**
     * 获取商户加密秘钥
     * @param  array $params 商户请求数据
     * @return string
     */
    public function getMerchantKey($params)
    {
        //根据商户号取得key值
        $merchant_data = User::where('mobile', '=', $params['merchant_no'])->Enabled()->first();

        $this->merchant_data = $merchant_data;
        if (empty($merchant_data) || $merchant_data->key == '') {
            return false;
        }
        $this->user_id = $merchant_data->id;
        return $merchant_data->key;
    }

    /**
     * 返回错误内容
     * @param  string $code 错误码
     * @return string
     */
    protected function getError($code)
    {
        return $this->error->getError($code, $this->error_code_show);
    }



    /**
     * 检查商户是否可以使用该渠道
     * @param $user_id number
     * @param $channel_id number
     * @return array  执行结果
     */
    protected function checkChannelAuth($user_id, $channel_id)
    {
        $result = UserAgentChannel::where('user_id', '=', $user_id)->where('channel_id', '=', $channel_id)->first();

        if (!empty($result)) {
            return [
                'channel_rate' => $result->channel_rate,
                'channel_fee' => $result->channel_fee,
            ];
        }
        return false;
    }





    /**
     * 判断执行路径文件是否存在
     * @param  string $pay_route 执行的路径方法
     * @param  string $name_space 检查文件的路径
     * @return string|false
     */
    protected function checkRoute($pay_route, $name_space = null)
    {

        $className = self::getClassName($pay_route);

        $name_space = $name_space ?? __NAMESPACE__;

        $classPath = $name_space . '\\Response\\' . $className;

        if (!$className || !class_exists($classPath)) {
            return $this->response(['status' => false, 'code' => '1008']);
        }

        if (!method_exists($classPath, 'run')) {
            return $this->response(['status' => false, 'code' => '1009']);
        }

        $this->classname = $classPath;
        $class = new $classPath;

        // 判断方法是否存在 并分发接口
        if (method_exists($classPath, 'checkParams')) {
            return $this->response((array)$class->checkParams($this->params));
        } else {
            return $this->response((array)$class->run($this->params));
        }
    }

    /**
     * 通过方法名转换为对应的类名
     * @param  string $method 方法名
     * @return string|false
     */
    protected function getClassName($method)
    {
        $methods = explode('.', $method);

        if (!is_array($methods))
            return false;

        $tmp = array();
        foreach ($methods as $value) {
            $tmp[] = ucwords($value);
        }

        $className = implode('', $tmp);
        return $className;
    }

    /**
     * 输出结果
     * @param  array $result 结果
     * @return string|boolean
     */
    protected function response(array $result)
    {
        if (!array_key_exists('msg', $result) && array_key_exists('code', $result)) {
            $result['msg'] = $this->getError($result['code']);
        }

        if ($this->format == 'json') {
            //return response()->json($result);
            return json_encode($result);
        }

        return false;
    }

    /**
     * 接收异步通知时输出结果
     * @param $result array 结果
     * @param  $type =0 number 通知类型 0为普通订单通知，1为提现通知
     * @return boolean|string
     */
    protected function callbackResponse($result, $type = 0)
    {
        $result = json_decode($result, true);
        if (!array_key_exists('status', $result) || $result['status'] == FALSE) {
            return 'error';
        }

        //给下游发送异步通知
        $is_receive_callback = $this->sendOrderCallback($result['order_id'], $type);
        if ($is_receive_callback) {
            if (array_key_exists('msg', $result)) {
                return $result['msg'];
            }
            return 'success';
        }

        return 'error';
    }

    /**
     * 发送下游商户异步通知结果
     * @param  $order_id number 订单id
     * @param  $type =0 number 通知类型 0为普通订单通知，1为提现通知
     * @return boolean|string
     */
    protected function sendOrderCallback($order_id, $type = 0)
    {
        if ($type == 0) {
            $order_data = Order::find($order_id);

            $data = [
                'pay_status' => $order_data->pay_status,
                'paid_at' => $order_data->paid_at,
                'out_order_no' => $order_data->merchant_out_order_no,
                'order_no' => $order_data->order_no,
                'sign_type' => $this->sign_method,
                'amount'=>$order_data->trade_amount,
            ];

            //预留的订单商户备注信息
            //$note=json_decode($order_data->note);
            //if(isset($note->reserved)){
                //$data['reserved']=$note->reserved;
            //};
        } elseif ($type == 1) {
            return ;
           /* $order_data = Withdrawal::find($order_id);

            $data = [
                'platform_trade_no' => $order_data->self_trade_no,
                'pay_status' => $order_data->status,
                'out_req_sn' => $order_data->out_req_sn,
                'sign_type' => $this->sign_method,
                'total_fee'=>$order_data->total_fee
            ];*/
        }

        $key = User::select('key')->where('id', '=', $order_data->user_id)->value('key');
        //根据订单提取发送的数据，进行加密
        $sign = $this->generateSign($data, $key);
        $data['sign'] = $sign;
        $data['notify_url'] = $order_data->notify_url;

        //dispatch(new SendCallbackMsg($data));//执行异步通知队列，  12-19取消队列, 多次通知改为让上游通知
        $sendCallBack = new SendCallbackMsg($data);
        return $sendCallBack->handle();

    }


    /**
     * 检查当前时间，交易时间在$start-$end之间
     * @param $start =null  开始时间
     * @param $end =null    结束时间
     * @return boolean|string
     */
    public function checkTime($start = null, $end = null)
    {

        if (!is_null($start) && !is_null($end)) {
            $hour = date("H", time());
            if ($hour >= $start && $hour < $end) {
                return true;
            }
            return false;
        }
        return true;  //2018-1-10 无时间限制
        /* $hour= date("H",time());
         if($hour>7 && $hour <23){
             return true;
         }
         return false;*/
    }

}