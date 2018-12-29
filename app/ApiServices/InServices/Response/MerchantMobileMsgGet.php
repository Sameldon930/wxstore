<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 16:03
 */

namespace App\ApiServices\InServices\Response;
use App\BankBranch;
use App\Libs\MobileMsg\MobileMsgSend;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Validator;

class MerchantMobileMsgGet extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 4007
     */
    protected $method = 'MerchantMobileMsgGet';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'mobile' => 'required|numeric|digits:11',
        ];
        $messages = [
            'mobile.required' => '手机号码缺失',
            'mobile.numeric' => '手机号码必须为数字',
            'mobile.digits' => '手机号码必须为11位',
        ];

        $v = Validator::make($params, $rules, $messages);
        // dd( $v);
        if ($v->fails()) {
            return [
                'status' => false,
                'code' => '2001',
                'msg' => $v->errors()->all()
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
        $err_1 = ['status' => false, 'code' => '50020001', 'msg' => '验证码获取失败,请重试'];
        $err_2 = ['status' => false, 'code' => '50020002', 'msg' => '请勿频繁获取手机验证码'];
        $err_3 = ['status' => false, 'code' => '50020003', 'msg' => '该手机号已注册'];
        $err_4 = ['status' => false, 'code' => '50020004', 'msg' => '正在发送手机短信,请稍等'];

        $mobile = $params['mobile'];
        //校验手机号,如果API接口有这个请求的话
        if (isset($params['check'])) {

            $check_mobile = User::where('real_mobile', '=', $mobile)->first();
            if (!empty($check_mobile)) {
                return $err_3;
            }
        }
        $mobile_code = rand(1000, 9999);
        if (Cache::store('file')->get($mobile . '_mobile_code' . '_has')) {
            return $err_2;
        };

        //防止向短信供应商提交申请延迟 从而导致的多次发送短信结果的问题
        /*if (Cache::store('file')->get($mobile . '_wait' . '_result') != null) {
            return $err_4;
        }*/
        Cache::store('file')->put($mobile . '_wait' . '_result', 1, Carbon::now()->addMinutes(1));

        //发送验证码
        $send_mobile_msg = new MobileMsgSend();

        $result = $send_mobile_msg->send($mobile, $mobile_code,null,!env('APP_DEBUG'));
//        $result = $send_mobile_msg->send($mobile, $mobile_code,null,false);

        //$result=$this->sendMessage($mobile,$mobile_code);

        Cache::store('file')->pull($mobile . '_wait' . '_result');

        if ($result['respCode'] == '000000') {
            Cache::store('file')->put($mobile . '_mobile_code' . '_has', 1, Carbon::now()->addMinutes(1));
            Cache::store('file')->put($mobile . '_mobile_code', $mobile_code, Carbon::now()->addMinutes(20)); //缓存验证码,可以做更多的验证处理,防止重复获取

            return [
                'status' => true,
                'code' => '200',
                'data' => '发送成功',
                'msg' => '短信发送成功',
            ];
        } else {
            return $err_1;
        }
    }
}