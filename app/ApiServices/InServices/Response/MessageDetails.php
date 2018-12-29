<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 16:35
 */

namespace App\ApiServices\InServices\Response;

use App\Message;
use App\Order;
use Illuminate\Support\Facades\DB;
use Validator;
use Carbon\Carbon;
class MessageDetails extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'MessageDetails';

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
        $data = Message::find($params['id']);
        $data->now = date('Y-m-d',strtotime($data->now));
        return [
            'status'=>true,
            'code'=>'200',
            'data'=>$data
        ];
    }
}