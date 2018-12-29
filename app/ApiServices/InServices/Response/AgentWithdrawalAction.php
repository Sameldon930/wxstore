<?php
namespace App\ApiServices\InServices\Response;

use App\AccountLog;
use App\ApiLog;
use App\Libs\CloudPay\CloudPay;
use App\UserAccount;
use App\UserSavingsCard;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Withdrawal;
use App\User;

/**
 * api测试类
 */
class
AgentWithdrawalAction extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * write xia
     * 接口id 1015
     */
    protected $method = 'WithdrawalAddOne';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        //检验登录状态
        $is_login = new LoginCheck;
        if (!$is_login->agentlogin_verify($params)){
            return ['status' => false, 'code' => '0005', 'msg' => '还未登录'];
        };
//        dd(123213);
        /*$hour= date("H",time());
        if( $hour >=16){
            return ['status' => false, 'code' => '20110008', 'msg' => '提现时间为00:00-16:00'];
        }*/
        //接口必要参数检验
        $rules = [
            'money' => 'required|numeric|min:10',
            'savings_bank_id' => 'required|numeric',

        ];
        $messages = [
            'money.required' => '提现金额缺失',
            'money.numeric' => '提现金额必须为数字',
            'money.min' => '最低提现金额为10',
            'savings_bank_id.required' => '提现到账卡必须选择',
            'savings_bank_id.numeric' => '提现到账卡必须选择',
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

        $err_1 = ['status' => false, 'code' => '20110001', 'msg' => '账户可提现额度不足'];
        $err_2 = ['status' => false, 'code' => '20110002', 'msg' => '该用户被冻结'];
        $err_3 = ['status' => false, 'code' => '20110003', 'msg' => '提现账户错误，请重新选择。'];

        $err_4 = ['status' => false, 'code' => '20110004', 'msg' => '平台账户查询失败'];
        $err_5 = ['status' => false, 'code' => '20110005', 'msg' => '提现失败,请联系客服'];
        $err_6 = ['status' => false, 'code' => '20110006', 'msg' => '提现申请已发起，正在处理'];



        $user_id = $params['id'];
        $total_fee = yuanToFen($params['money']);
        //$withdraw_fee = $total_fee - 1;//扣掉1元平台手续费
        $withdraw_fee=$total_fee; //提现金额

        $saving_card_data = UserSavingsCard::where('user_id', '=', $user_id)->find($params['savings_bank_id']);
        if(empty($saving_card_data)){
            return $err_3;
        }
        $user_account_data = UserAccount::where('user_id','=',$params['id'])->get();

        $len=count($user_account_data);

        if($len==0){
            return $err_1;
        }
        $len--;
        $update_arr=[];
        $rest_money=$total_fee;
        for($i=0;$i<=$len;$i++){
            if(!isset($user_account_data[$i])){
                continue;
            }
            $balance=$user_account_data[$i]->balance;
            if($balance>=$rest_money){
                $update_arr[]=['id'=>$user_account_data[$i]->id,'fee'=>$rest_money];
                $rest_money=0;
                break;
            }else{
                $rest_money-=$balance;
                $update_arr[]=['id'=>$user_account_data[$i]->id,'fee'=>$balance];
            }
        }
        if($rest_money>0){
            return $err_1;
        }

        $user_data = User::find($user_id);

        if (intval($user_data->status) !== 1){
            return $err_2;
        }


        DB::beginTransaction();
        $req_sn = 'wis' . date('ymdHis', time()) . rand(10000, 99999);
        #增加提现订单 start
        $arr = array(
            'user_id' => $user_id,
            'tube_id' => 0,
            'out_order_no' => $req_sn,
            'order_no' => $req_sn,
            'trade_amount' => $total_fee,
            'real_amount' => $total_fee,
            'type' => Withdrawal::TYPE_ACCOUNT_BALANCE,
            'status' => Withdrawal::STATUS_WAITING,
            'note' => '用户提现',
        );
        $new_withdraw_data = Withdrawal::create($arr);
        #增加提现订单 end

        foreach($update_arr as $v){
            if($v['fee']==0){
                continue;
            }
            $user_account=UserAccount::lockForUpdate()->find($v['id']);

            $user_account->balance-=$v['fee'];
            $user_account->save();
            #增加流水 start
            $arr2=[
                'order_id'=>$new_withdraw_data->id,
                'no'=>date('YmdHis'). rand(1000, 9999),
                'user_id'=>$user_account->user_id,
                'amount'=>$v['fee'],
                'balance'=>$user_account->balance,
                'type'=>AccountLog::WITHDRAWAL_ACCOUNT_BALANCE,
                'flow'=>AccountLog::FLOW_OUT,
                'snap'=>'',
                'business'=>AccountLog::BUSINESS_WITHDRAWAL,
            ];

            AccountLog::create($arr2);
            #增加流水 end

        }
        DB::commit();

        //执行云派提现
        if(env('APP_DEBUG')){
            //测试环境模拟提现
            $data=['status'=>true];
        }else {
            //查询余额
            $cloudPay = new CloudPay();
            $data = $cloudPay->account(['account_type' => "PAY_ACCOUNT"]);
            if (isset($data) && $data['status']) {
                if ($data['data']['account'] < $withdraw_fee) {
                    return $err_3;
                }
            } else {
                return $err_4;
            }

            try{
                $arr_data['out_req_sn'] = $req_sn;
                $arr_data['bank_name'] = $saving_card_data->saving_bank->bank_name; //储蓄卡所在行
                $arr_data['bank_code'] = $saving_card_data->saving_bank->bank_code; //储蓄卡银行码
                $arr_data['bank_account_no'] = $saving_card_data->account_no;       //账户
                $arr_data['bank_account_name'] = $saving_card_data->account_name; //户名
                $arr_data['amount'] = $withdraw_fee;  //单位分数
                $arr_data['pay_type'] = 'CJPAY';  //支付类型
                $arr_data['notify_url'] = route('callback.cloudpay_callback');  //支付类型
                //发起转账

                $data = $cloudPay->withdraw($arr_data);
                ApiLog::create([
                    'user_id' => $user_id,
                    'note' => '提现-云付',
                    'request' => json_encode(['arr_data'=>$arr_data,'req_sn'=>$req_sn]),
                    'respond' => json_encode(['data' => json_encode($data)])
                ]);
            }catch (\Exception $e){
                return [
                    'status' => false,
                    'code' => '200',
                    'msg' => $e->getMessage(),

                ];
            }
        }

        if (empty($data)) {
            return $err_5;
        }

        if (isset($data) && !$data['status']) {
            return $err_6;
        }

        $return_data = array(
            'msg' => '提现申请已提交',
            'user_account' => $user_data->account,
            'withdraw_id' => $new_withdraw_data->id,
        );
        return [
            'status' => true,
            'code' => '200',
            'data' => $return_data,
            'msg' => '成功'
        ];
    }


}