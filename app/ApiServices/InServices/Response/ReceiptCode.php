<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16
 * Time: 10:40
 */

namespace App\ApiServices\InServices\Response;


use App\User;
use Validator;

use Illuminate\Foundation\Application;

class ReceiptCode extends BaseResponse implements InterfaceResponse
{
    protected $method = 'ReceiptCode';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => 'id缺失',
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

        $id = $params['id'];
        //商户信息内容
        $data = User::select('name','mobile')->where('id',$id)->first();
        //商户对应二维码
        $code = env('APP_URL') . '/pay?u=' . $data->mobile;
//        dd($code);
//        "http://localhost/pay?u=10004"

        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
             'model'=>$code
        ];
    }

}