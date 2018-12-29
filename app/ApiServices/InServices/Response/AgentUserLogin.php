<?php
namespace App\ApiServices\InServices\Response;

use App\User;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * api测试类
 */
//agent_login.html登录接口
class AgentUserLogin extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称 kui
     * @var string
     * 接口id 0001
     */
    protected $method = 'AgentUserLogin';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'real_mobile' => 'required|min:5|max:11',
            'password' => 'required|min:6|max:255',
        ];
        $messages = [
            'real_mobile.required' => '手机号码缺失',
            'real_mobile.min' => '手机号码或商户账号最少5个字符',
            'real_mobile.max' => '手机号码或商户账号最多11个字符',
            'password.required' => '密码缺失',
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
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {

        $err_1 = ['status' => false, 'code' => '00020002', 'msg' => '手机号或密码错误,或登录身份不是代理'];
        $err_2 = ['status'=>false,'code'=>'00030003','msg'=>'该代理状态被冻结'];
        $mobile = $params['real_mobile'];
        $password = $params['password'];

        $check_level = User::select()
            ->orwhere('real_mobile', '=', $mobile)
            ->orwhere('mobile', '=', $mobile)
            ->first();
        if($check_level->status == 2)
            return $err_2;
        $check_password = User::select()
            ->orwhere('real_mobile', '=', $mobile)
            ->orwhere('mobile', '=', $mobile)
            ->value('password');
        if ($check_password == null )
            return $err_1;

        if (!Hash::check($password, $check_password))
            return $err_1;
              if($check_level->isAgent()){
                  $user_data = DB::table('users')
                      ->orwhere('real_mobile', '=', $mobile)
                      ->orwhere('mobile', '=', $mobile)
                      ->first();
                  return $this->login_action($user_data->id);
              }
       return $err_1;
    }

    /**
     * 执行登录,设置缓存,返回数据
     * @param  array &$params 请求参数
     * @return array
     */
    public function login_action($id)
    {
        $user_data = DB::table('users')
            ->select('id','name','mobile','real_mobile','password','remember_token','deleted_at','created_at','updated_at','level','aid','aids','status','avatar')
            ->find($id);
        $loginCheck = new AgentLoginCheck();
        $user_id = $user_data->id;
        $mobile = $user_data->mobile;
        $token = $loginCheck->encrypt($user_id . '_' . $user_data->password);
        $user_data->remember_token = $token;
        DB::table('users')
            ->where('id', $id)
            ->update(['remember_token' => $token]);
        Session::push('id', $user_id);
        Session::push('real_mobile', $mobile);
        Session::push('remember_token', $token);
        return [
            'status' => true,
            'code' => '200',
            'data' => $user_data,
            'msg' => '登录成功'
        ];
    }
    public function login_verify(&$params)
    {
        $api_token = User::where('id',$params['id'])->first()->remember_token;
        if($api_token == $params['token']) {
            return true;
        }
        return [
            'status' => true,
            'code' => '005',
            'msg' => '身份信息已过期，请重新登陆',
        ];
    }

}