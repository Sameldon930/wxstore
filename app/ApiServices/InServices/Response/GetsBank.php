<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 15:29
 */
//谢树文
namespace App\ApiServices\InServices\Response;

use App\Bank;
use Validator;

class GetsBank extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 4007
     */
    protected $method = 'GetsBank';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
//        dd($params);
        $rules = [];
        $messages = [];

        $v = Validator::make($params, $rules, $messages);

        if ($v->fails()) {
            return ['status' => false, 'code' => '2001', 'msg' => $v->errors()->all()];
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
        $bank_lists = Bank::where('status', '=', 1)
            ->orderBy('order','desc')
            ->get()->toArray();
//dd($bank_lists);
        return [
            'status' => true,
            'code' => '200',
            'data' => $bank_lists,
            'msg' => '请求成功'
        ];
    }
}