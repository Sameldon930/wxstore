<?php

namespace App\ApiServices\InServices\Response;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class StoreResePwd extends BaseResponse implements InterfaceResponse
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
     //商户修改密码接口 页面:user_modify.html
    public function checkParams(&$params)
    {
        return $this->run($params);
        $rules = [
            'mobile' => 'required|min:11|max:11',
            'password' => 'required|min:6|max:255',
            'mobile_code' => 'required|numeric',
        ];
        $messages = [
            'password.min' => '密码最少6个字符',
            'password.max' => '密码最多255个字符',
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
     * @param $params
     * @return array
     */
    public function run(&$params)
    {
        $err_1 = ['status' => false, 'code' => '00040001', 'msg' => '旧密码错误'];
        $err_2 = ['status' => false, 'code' => '00040002', 'msg' => '新旧密码一致'];
        $err_3 = ['status' => false, 'code' => '00040003', 'msg' => '修改失败,请联系管理员'];
        $err_4 = ['status' => false, 'code' => '00040003', 'msg' => '新密码，确认密码不一致'];
        //接受前台传入的值
        $old_password = $params['old_pwd'];
        $new_password = $params['password'];
        $new_password_check = $params['password_confirm'];
        //比较新密码和确认密码是否一致
        if ($new_password != $new_password_check) {
            return $err_4;
        }
        $user_id = $params['user_id'];
        if ($old_password == $new_password) {
            return $err_2;
        }
        //查询此商户的密码
        $check_password = DB::table('users')->where('id', '=', $user_id)->value('password');
        //借助Hash的check方法比较密码
        if (!Hash::check($old_password, $check_password))
            return $err_1;
        //通过前面验证后进行加密
        $new_password = bcrypt($new_password);
        //执行修改操作
        $result = DB::table('users')->where('id', '=', $user_id)->update(['password'=>$new_password]);
        if (!$result)
            return $err_3;
        return [
            'status' => true,
            'code' => '200',
            'msg' => '修改密码成功'
        ];
    }



}