<?php
namespace App\ApiServices\InServices\Response;


use Illuminate\Support\Facades\DB;
use Validator;

/**
 * api获取用户银行卡列表my_card
 * author:pang
 */
//谢树文
class CreditCardGetList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 2001
     */
    protected $method = 'CreditCardGetList';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
//        dd($params);
//         检验登录状态
        $rules = [
            'id' => 'required',
            'remember_token' => 'required'
        ];
        $messages = [
            'id.required' => '用户ID缺失',
            'remember_token.required' => '用户token值缺失'
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
        $user_id = $params['id'];

        $cards_list = DB::table('user_savings_cards')
            ->join('card_savings_banks', 'card_savings_banks.id', '=', 'user_savings_cards.saving_bank_id')
            ->select('user_savings_cards.id', 'card_savings_banks.bank_name', 'card_savings_banks.url', 'user_savings_cards.account_no',    'user_savings_cards.created_at')
            ->where('user_savings_cards.user_id', $user_id)
            ->where('user_savings_cards.deleted_at', '=',null)
            ->orderBy('created_at', 'desc')
            ->get();
        return [
            'status' => true,
            'code' => '200',
            'data' => $cards_list,
            'msg' => '请求成功'
        ];
    }


}