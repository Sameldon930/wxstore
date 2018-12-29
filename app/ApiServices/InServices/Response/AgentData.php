<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/30
 * Time: 16:29
 */

namespace App\ApiServices\InServices\Response;

use App\User;
use App\UserAgentInfo;
use Validator;
//代理详细信息agentinfo.html
//谢树文
class AgentData extends BaseResponse implements InterfaceResponse
{
    protected $method = 'AgentData';
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
        //请求参数
        //method = demo
        //nonce = demo
        //sign = 4FE4E806FC10025458AE1BBA32655833
        $id = $params['id'];
        $data = UserAgentInfo::select()
            ->where('user_id',$id)
            ->where('user_agent_infos.deleted_at','=',null)
            ->get();

        foreach ($data as $value) {
            $value->type = UserAgentInfo::TYPES[$value->type];

        }
        unset($value);
        return [
            'status' => true,
            'code' => '200',
            'data' => $data
        ];
    }
}