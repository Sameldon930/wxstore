<?php
namespace App\ApiServices\InServices\Response;

use App\Message;
use App\Withdrawal;
use Validator;

/**
 * api测试类
 */
class SystemList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'SystemList';

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

        $page_num = $params['page_num'];
        $data = Message::select()
                ->where('status','=','1')
                ->orderBy('messages.id',"desc")
                ->paginate(8, null, null, $page_num);
        foreach($data as &$val){
            $val->now = date('Y-m-d',strtotime($val->now));
        }
        unset($val);
        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
        ];
    }


}