<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/31
 * Time: 18:04
 */

namespace App\ApiServices\InServices\Response;

use App\MerchantInfo;
use App\SavingCard;
use App\User_info_check;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Jobs\WxMsgSendAction;

//银行卡添加
//谢树文
class AgentCardUpdate extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     * 接口id 2009
     */
    protected $method = 'SavingsCardUpdate';

    /**
     * 接口参数检验
     */
    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required',
            'account_name' => 'required',
            'account_no' => 'required|numeric|digits_between:15,19',
        ];
        $messages = [
            'id.required' => '用户ID缺失',
            'account_name.required' => '姓名缺失',
            'account_no.required' => '银行卡卡号缺失',
            'account_no.numeric' => '银行卡卡号必须为数值',
            'account_no.digits_between' => '银行卡卡号位数错误',
        ];

        $v = Validator::make($params, $rules, $messages);

        if ($v->fails()) {
            return ['status' => false, 'code' => '2001', 'msg' => $v->errors()->all()];
        } else {
            return $this->run($params);
        }

    }

    public function run(&$params){

        $err_1 = ['status' => false, 'code' => '20030005', 'msg' => '已经存在相同卡号的银行卡'];

        $time = date("Y-m-d H:i:s");
        $create_data = [
            'user_id' => $params['id'],
            'saving_bank_id' => $params['doc_select'],
            'account_no' => $params['account_no'],
            'account_name' => $params['account_name'],
            'opening_account' => $params['provs'].'-'.$params['city'].'-'.$params['zhihang'],
            'created_at' => $time,
            'updated_at' => $time,
        ];

        $check_account_no = DB::table('user_savings_cards')
            ->where('user_id', '=', $params['id'])
            ->where('account_no', '=', $params['account_no'])
            ->first();
//        dd($check_account_no);
        //如果这条记录不为空 并且 删除时间为空(没有被删除) 就会报错
        if (empty($check_account_no) == false && empty($check_account_no->deleted_at)==true ){
            return $err_1;
        }
        $sql =  DB::table('user_savings_cards')
            ->where('user_savings_cards.user_id','=',$params['id'])
            ->where('deleted_at','=',null)
            ->select('user_id')->get()->count();
        if ($sql>=3){
            return [
                'status' => true,
                'code' => '0006',
                'msg' => '最多可以添加3张银行卡',
            ];
        }
        $data = DB::table('user_savings_cards')->where('user_savings_cards.user_id','=',$params['id'])->insert($create_data);
        return [
            'status' => true,
            'code' => '200',
            'data'=>$data,
            'msg' => '请求成功',
        ];
    }

}