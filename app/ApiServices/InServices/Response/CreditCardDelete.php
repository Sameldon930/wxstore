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
//删除银行卡my_card
//谢树文
class CreditCardDelete extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 2004
     */
    protected $method = 'CreditCardDelete';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
//        $is_login = new LoginCheck;
//        if (!$is_login->login_check($params)) {
//            return ['status' => false, 'code' => '0005', 'msg' => '还未登录'];
//        };

        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => '用户银行卡ID缺失',
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
        $err_1 = ['status' => false, 'code' => '20040001', 'msg' => '删除失败，请确保删除的是本人的信用卡',];
        $id = $params['id'];
        $time = date("Y-m-d H:i:s");
        $result = DB::table('user_savings_cards')
            ->where('id', '=', $id)
            ->where('user_id', '=', $params['user_id'])
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