<?php
namespace App\ApiServices\InServices\Response;

use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\User;

/**
 * api测试类
 */
class AdminAgentChangePassword extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称 MobileMsgCheck
     * @var string
     * 接口id 5001
     */
    protected $method = 'AdminAgentChangePassword';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'mobile'      => 'required|numeric|digits:11',
            'mobile_code' => 'required|numeric|digits_between:4,10',
            'password' =>   'required|digits_between:6,20',
        ];
        $messages = [
            'mobile.required' => '手机号码缺失',
            'mobile.numeric' => '手机号码必须为数字',
            'mobile.digits' => '手机号码必须为11位',
            'mobile_code.digits_between' => '短信验证码长度在4-10之间',
            'password.required' => '密码必须填写',
            'password.digits_between' => '密码长度6-20个字符',

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
        $err_1 = ['status' => false, 'code' => '40020001', 'msg' => '用户错误'];
        $err_2 = ['status' => false, 'code' => '40020002', 'msg' => '验证码错误,验证失败'];
        $user=User::where('real_mobile','=',$params['mobile'])->first();
        if(empty($user)){
            return $err_1;
        }

        $cache_mobile_code = Cache::store('file')->get($params['mobile'] . '_mobile_code');

        if ($cache_mobile_code != $params['mobile_code']) {
            return $err_2;
        }

        Cache::store('file')->pull($params['mobile'] . '_mobile_code');

        $newPassword=bcrypt($params['password']);
        $user->update(['password'=>$newPassword]);

        return [
            'status' => true,
            'code' => '200',
            'data' => ['status' => 1],
            'msg' => '修改成功'
        ];

    }


}