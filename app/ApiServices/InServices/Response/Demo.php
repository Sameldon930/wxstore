<?php
namespace App\ApiServices\InServices\Response;

use Validator;

/**
 * api测试类
 */
class Demo extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'demo';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'param1' => 'required|min:1'
        ];
        $messages = [
            'param1.required' => 'param1缺失',
            'param1.min' => 'param1最少1个字符'
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
        //请求参数
        //method = demo
        //nonce = demo
        //sign = 4FE4E806FC10025458AE1BBA32655833

        return [
            'status' => true,
            'code' => '200',
            'data' => [
                'test' => 123,
            ]
        ];
    }


}