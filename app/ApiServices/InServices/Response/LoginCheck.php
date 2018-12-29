<?php
namespace App\ApiServices\InServices\Response;

use App\UserLiveness;
use App\User;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * api测试类
 */
class LoginCheck extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称 kui
     * @var string
     * 接口id 0005
     */
    protected $method = 'LoginCheck';
    protected $key = 'Wis_g_'; //秘钥


    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $token = $params['remember_token'];
        $id = $params['id'];

        $array = [
            'status' => false,
            'code' => '0005',
            'msg' => '还未登录'
        ];

        /* $array2=[
             'status' => false,
             'code'   => 0006,
             'msg'    => '用户信息已经修改,请重新登录!'
         ];*/

        //echo Session::get('name');die;
        if ($token == '' || $id == '') {
            return $array;
        }
        //$user_data=DB::table('users')->where('id','=',$user_id)->first();
        /*if($user_data->mobile!=Session::get('mobile')){
            return $array2;
        }*/

        if (Session::get('remember_token') == $token && Session::get('id') == $id) {
            return $this->run($params);
        }

        $user_data = DB::table('users')
            ->where('id', '=', $id)->first();
        $remember_token = $user_data->remember_token;//密码
        if($remember_token==$params['remember_token']){
            return $this->run($params);
        } else {
            return $array;
        }

    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        return [
            'status' => true,
            'code' => '200',
            'data' => [
                'msg' => '已经登录',
            ]
        ];
    }

    /**
     * 为其他接口提供用户登录检验
     * @param  array &$params 请求参数
     * @return boolean
     */
    public function login_check(&$params)
    {
        $token = isset($params['remember_token']) ? $params['remember_token'] : '';
        $id = isset($params['id']) ? $params['id'] : '';
        if ($token == '' || $id == '') {
            return false;
        }

        if (Session::get('remember_token') == $token && Session::get('id') == $id) {
            return $this->check_status($params);
        }

        $password = DB::table('users')->where('id', '=', $id)->value('password');//密码
        if ($token == $this->encrypt($id . '_' . $password)) {
            Session::put('remember_token', $this->encrypt($id . '_' . $password));
            Session::put('id', $id);
            return $this->check_status($params);
        } else {
            return false;
        }

    }

    //检查用户是否被冻结
    public function check_status(&$params)
    {
        if (!Cache::store('file')->has('is_lock') || Cache::store('file')->get('is_lock') == 1) {
            $user_data = DB::table('users')->select('id', 'status')
                ->find($params['id']);
            if ($user_data->status == 1) {  //冻结状态
                Cache::store('file')->put('is_lock', 1, 1);
                return false;
            } else {
                Cache::store('file')->put('is_lock', 2, 1);
                UserLiveness::updateLastLogined($params['user_id']);
                return true;
            }
        }

        if (Cache::store('file')->get('is_lock') == 2) {
            UserLiveness::updateLastLogined($params['user_id']);
            return true;
        }
    }

    public function encrypt($str)
    {
        return MD5($this->key . $str);

    }
    public function login_verify(&$params)
    {
        $api_token = User::where('id',$params['id'])->first()->remember_token;
        $inspect = User::where('id',$params['id'])->first();
          if ($inspect->isMerchant()){
              if($api_token == $params['remember_token']) {
                  return true;
              }
          }
        return false;
    }

    //判断如果是代理的话 就不能登录商户端
    public function agentlogin_verify(&$params)
    {
        $api_token = User::where('id',$params['id'])->first()->remember_token;
        $inspect = User::where('id',$params['id'])->first();
        if ($inspect->isAgent()){
            if($api_token == $params['remember_token']) {
                return true;
            }
        }
        return false;
    }
    //判断是否是门店
    public function StoreLogin_verify(&$params)
    {
        $api_token = User::where('id',$params['id'])->first()->remember_token;
        $inspect = User::where('id',$params['id'])->first();
        if ($inspect->isStore()){
            if($api_token == $params['remember_token']) {
                return true;
            }
        }
        return false;
    }
}