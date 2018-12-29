<?php
namespace App\ApiServices\InServices\Response;

use App\User;
use App\UserAccount;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 * api测试类
 */
class AgentWithdraw extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'AgentWithdraw';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {


            return $this->run($params);

    }

    /**
     * 执行接口
     * @param  array &$params 请求参数
     * @return array
     */
    public function run(&$params)
    {
        //当前代理的余额
        $data = UserAccount::select()
                ->where('user_id','=',$params['id'])
                ->sum('balance');
        $bank = DB::table('user_savings_cards')
            ->join('card_savings_banks','card_savings_banks.id','=','user_savings_cards.saving_bank_id')
            ->select('user_savings_cards.account_no','user_savings_cards.id','card_savings_banks.bank_name')
            ->where('user_savings_cards.user_id','=',$params['id'])
            ->where('user_savings_cards.deleted_at','=',null)
            ->get();
        return [
            'status' => true,
            'code' => '200',
            'data' => fenToYuan($data),
            'bank' => $bank,
        ];
    }


}