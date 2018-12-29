<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 10:50
 */

namespace App\ApiServices\InServices\Response;
use App\User;
use Validator;
use App\UserAgentInfo;

class UserInfo extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'UserInfo';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
            return $this->run($params);

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
        $data = User::join('user_agent_infos','user_id','=','users.id')->where('users.id',$id)->first();
        return [
            'status' => true,
            'code' => '200',
            'data' => $data
        ];
    }
}