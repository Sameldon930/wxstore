<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/30
 * Time: 15:27
 */

namespace App\ApiServices\InServices\Response;

use App\User;
use Validator;
//代理信息和头像接口
//谢树文，张泽山
class AgentInfo extends BaseResponse implements InterfaceResponse
{
    protected $method = 'AgentInfo';
    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        //检验登录状态
        $is_login = new LoginCheck;
        if (!$is_login->agentlogin_verify($params)){
            return ['status' => false, 'code' => '0005', 'msg' => '还未登录'];
        };
        $rules = [
            'id' => 'required',
            'remember_token' => 'required'
        ];
        $messages = [
            'id.required' => 'ID缺失',
            'remember_token.required' => '身份校验失败'
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
//         dd($params);
        $data = User::select('users.name','users.mobile','users.avatar','real_mobile')
            ->where('users.id',$params['id'])
            ->first();

        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
        ];
    }
}