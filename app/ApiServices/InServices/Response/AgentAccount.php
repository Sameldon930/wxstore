<?php
namespace App\ApiServices\InServices\Response;

use App\UserAccount;
use Validator;

/**
 * api测试类
 */
//account.html代理余额账户
//张泽山
class AgentAccount extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'AgentAccount';

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
        //当前代理的余额
        $data = UserAccount::select()
            ->where('user_id','=', $params['id'])
            ->sum('balance');
//          dd($data);
        return [
            'status' => true,
            'code' => '200',
            'data' => fenToYuan($data),
        ];
    }


}