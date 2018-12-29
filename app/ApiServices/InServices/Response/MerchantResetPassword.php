<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 16:03
 */

namespace App\ApiServices\InServices\Response;
use App\BankBranch;
use App\User;
use Illuminate\Support\Facades\Cache;
use Validator;

class MerchantResetPassword extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 4007
     */
    protected $method = 'MerchantResetPassword';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        return $this->run($params);
        $rules = [
            'mobile' => 'required|min:11|max:11',
            'password' => 'required|min:6|max:255',
            'mobile_code' => 'required|numeric',
        ];
        $messages = [
            'mobile.required' => '手机号码缺失',
            'mobile.min' => '手机号码最少11个字符',
            'mobile.max' => '手机号码最多11个字符',
            'password.required' => '手机号码缺失',
            'password.min' => '密码最少6个字符',
            'password.max' => '密码最多255个字符',
            'mobile_code.required' => '短信验证缺失',
            'mobile_code.numeric' => '短信验证必须为数字',
        ];
        $v = Validator::make($params, $rules, $messages);
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
        $err_1 = ['status' => false, 'code' => '00020002', 'msg' => '该账号不存在'];
        $err_2 = ['status' => false, 'code' => '00020002', 'msg' => '手机号错误'];
        $err_4 = ['status' => false, 'code' => '00020004', 'msg' => '手机验证码错误'];


        $mobile_code = $params['mobile_code'];
        $mobile = $params['mobile'];
        $password = $params['password'];

        if (!isMobile($params['mobile'])) {
            return $err_2;
        };

        //校验手机验证码
        if (!$this->mobile_code_check($mobile, $mobile_code))
            return $err_4;

        $password = bcrypt($password);

        $api_token = str_random(60);

        $has_mobile = User::where('real_mobile', '=', $mobile)->first();
        if (!empty($has_mobile)) {
            $create_data = [
                'password'=>$password,
                'api_token'=>$api_token,
            ];
            $has_mobile->update($create_data);
            return [
                'status' => true,
                'code' => '200',
                'msg' => '密码修改成功'
            ];
        } else {
            return $err_1;
        }
    }


        /**
         * 验证短信验证码
         * @param $mobile
         * @param $mobile_code
         * @return bool
         */
    public function mobile_code_check($mobile, $mobile_code)
    {

        $cache_mobile_code = Cache::store('file')->get($mobile . '_mobile_code');
        if ($cache_mobile_code != $mobile_code) {
            return false;
        }
        Cache::store('file')->pull($mobile . '_mobile_code');
        return true;
    }
}