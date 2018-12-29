<?php
namespace App\ApiServices\InServices\Response;

use App\Withdrawal;
use Validator;

/**
 * api测试类
 */
class WithdrawDetail extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'WithdrawDetail';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {


        //检验登录状态
//        $is_login = new AgentLoginCheck;
//        if (!$is_login->login_check($params)) {
//            return [
//                'status' => false,
//                'code' => '0005',
//                'msg' => '还未登录'
//            ];
//        };

        //接口必要参数检验
        $rules = [
            'id' => 'required|numeric',
        ];
        $messages = [
            'id.required' => '用户缺失',
            'id.numeric' => '用户ID必须为数字',
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
        $data = Withdrawal::select()
                ->where('withdrawals.user_id','=',$params['id'])
                ->orderBy('withdrawals.id',"desc")
                ->paginate(8, null, null, $page_num);
        foreach($data as &$val){
            $val->time = date('Y-m-d',strtotime($val->created_at));
            $val->status  = Withdrawal::STATUS[$val->status];
            $val->type = Withdrawal::TYPES[$val->type];
        }
        unset($val);
        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
        ];
    }


}