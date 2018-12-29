<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/15
 * Time: 15:28
 */

namespace App\ApiServices\InServices\Response;

//谢树文
//my.html
use App\MerchantInfo;
use App\User;
use Validator;
class UserGetOne extends BaseResponse implements InterfaceResponse
{
    protected $method = 'UserGetOne';

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

        $data = User::select('name','mobile','real_mobile','avatar')->find($params['id']);
        $status = MerchantInfo::where('user_id',$params['id'])->first();
        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
            'status' => $status,
        ];
    }

}