<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 10:53
 */

namespace App\ApiServices\InServices\Response;
use Illuminate\Support\Facades\DB;
use Validator;
class StoresDelete extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 2004
     */
    protected $method = 'StoresDelete';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => '门店ID缺失',
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
        $err_1 = ['status' => false, 'code' => '20040001', 'msg' => '删除失败，请确保删除的是旗下的门店',];
        $id = $params['id'];
        $time = date("Y-m-d H:i:s");
        $result = DB::table('users')
            ->where('id', '=', $id)
            ->update(['deleted_at'=>$time]);
        if (!$result) {
            return $err_1;
        }

        return [
            'status' => true,
            'code' => '200',
            'msg' => '删除成功',
        ];
    }
}