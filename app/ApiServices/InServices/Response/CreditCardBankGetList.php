<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 13:51
 */

namespace App\ApiServices\InServices\Response;
use App\CardCreditBank;
use App\SavingCard;
use Illuminate\Support\Facades\DB;
use Validator;
//银行卡列表页my_add_card
//谢树文
class CreditCardBankGetList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 4007
     */
    protected $method = 'CreditCardBankGetList';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required',
            'remember_token' => 'required'
        ];
        $messages = [
            'id.required' => 'ID缺失',
            'remember_token.required' => '身份校验失败'
        ];


        $v = Validator::make($params, $rules, $messages);
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
        $bank_lists = DB::table("card_savings_banks")->select("id", 'bank_name')->get();
        return [
            'status' => true,
            'code' => '200',
            'data' => $bank_lists,
            'msg' => '请求成功'
        ];
    }
}