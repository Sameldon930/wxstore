<?php
namespace App\ApiServices\InServices\Response;

use App\AccountLog;
use App\Order;
use App\SettleLog;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * api测试类
 */
//agent_bussiness.html旗下商户接口
class AgentAccountList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'AgentAccountList';

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

            //请求参数
            $time_select = isset($params['columns']) ? $params['columns'] : 'all'; //时间选择

            switch ($time_select){
                case '交易时间':
                    $time_start = '';
                    $time_end = Carbon::tomorrow();
                    break;
                case '今日':
                    //今日
                    $time_start =  Carbon::today();
                    $time_end = Carbon::tomorrow();
                    break;
                case '近七日':
                    //近七日
                    $time_start = Carbon::now()->subDays(7);
                    $time_end = Carbon::today();
                    break;
                case '本周':
                    //本周
                    $time_start =  Carbon::now()->startOfWeek();
                    $time_end = Carbon::now()->endOfWeek();
                    break;
                case '上周':
                    //上周
                    $time_start =  Carbon::now()->subWeek(1)->startOfWeek();
                    $time_end =Carbon::now()->subweek(1)->endOfWeek();
                    break;
                case '本月':
                    //本月
                    $time_start = Carbon::now()->startOfMonth();
                    $time_end = Carbon::now()->endOfMonth();
                    break;
                default:
                    //近三月
                    $time_start = Carbon::now()->subMonth(2)->startOfMonth();
                    $time_end = Carbon::tomorrow();
                    break;
            }
            $benefit = AccountLog::with('user','settle_log')
                ->where('account_logs.user_id',$params['id'])
                ->whereBetween('created_at',[$time_start,$time_end])
                ->sum('amount');

                //获取所有旗下商户收益总金额
                $user = User::find($params['id']);
                $money =  Order::with('user')
                ->merchant($user)
                ->whereBetween('created_at',[$time_start,$time_end])
                 ->Paid()
                ->sum('real_amount');
            $data1= User::merchant()
                ->where('aid','=',$params['id'])
                ->count();
            $data = User::merchant()
                ->where('aid','=',$params['id'])
                ->get();
            //代理总分润
//            $benefit = AccountLog::with('user','settle_log')->where('account_logs.user_id',$params['id'])->sum('amount');
//            //旗下商户数量
//            $data1= User::merchant()->where('aid','=',$params['id'])->count();
//            //旗下商户列表
//            $data = User::merchant()->where('aid','=',$params['id'])->get();
            return [
                'status' => true,
                'code' => '200',
                'data' => $data,
                'data1'=>$data1,
                'money'=>fenToYuan($money),
                'benefit'=>fenToYuan($benefit),
            ];
        }




}