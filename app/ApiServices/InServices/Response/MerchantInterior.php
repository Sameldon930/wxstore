<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/20
 * Time: 17:40
 */

namespace App\ApiServices\InServices\Response;
use Validator;
use App\MerchantInfo;

class MerchantInterior extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'MerchantInterior';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {  $login = new LoginCheck();
        if(!$login->login_verify($params)){
            return [
                'status' => false,
                'code' => '005',
                'msg' => '请先登录'
            ];
        }
        $rules = [
            'id' => 'required',
        ];
        $messages = [
            'id.required' => '缺少商户id',
        ];

        $v = Validator::make($params, $rules, $messages);
        // dd( $v);
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

        $data = MerchantInfo::where('user_id',$params['id'])->first();
        if(empty($data)){
            return [
                'status' => true,
                'code' => '202',
                'data' => $data,
                'msg' => '用户还未填写相关信息'
            ];
        }

        $data->contract_tenancy = json_decode($data->interior_picture);
        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
        ];


    }
}