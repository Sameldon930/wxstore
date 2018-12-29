<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/30
 * Time: 18:14
 */

namespace App\ApiServices\InServices\Response;

use App\User;
use App\UserAgentInfo;
use Validator;
//身份证证件照正反照片cardphoto.html
//谢树文
class IdCard extends BaseResponse implements InterfaceResponse
{
    protected $method = 'IdCard';
    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required',
        ];
        $messages = [
            'id.required' => 'ID缺失',
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
        $data = UserAgentInfo::
        select('cleaner_idcard_front','cleaner_idcard_back')
            ->where('user_id',$id)
            ->where('user_agent_infos.deleted_at','=',null)
            ->first();
        return [
            'status' => true,
            'code' => '200',
            'data' => $data
        ];
    }
}