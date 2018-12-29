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
//agent_subordinate.html旗下代理接口
class SubordinateAccountList extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'SubordinateAccountList';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        //检验登录状态
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
        if($params['zone']==3){
            //请求参数
            switch ($params['columns']){
                case ($params['columns'] == '交易时间'):
                    $time_start = '';
                    $time_end = Carbon::tomorrow();
                    //代理总分润

                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                        })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
                case ($params['columns'] == '今日'):
                    //今日
                    $time_start = Carbon::now()->startOfDay();//今天的0.00开始
                    $time_end = Carbon::now()->endOfDay();//今天的23.59结束
                    //代理总分润
                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                             })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
                case ($params['columns'] == '近七日') :
                    //近七日
                    $time_start = Carbon::now()->startOfDay()->subDay(7);//七天前的0.00
                    $time_end = Carbon::now()->endOfDay();//今天的23.59
                    //代理总分润
                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                            })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
                case ($params['columns'] == '本周') :
                    //本周
                    $time_start =  Carbon::now()->startOfWeek();//周一的0.00开始
                    $time_end = Carbon::now()->endOfWeek();//周日的23.59结束
                    //代理总分润
                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                            })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
                case ($params['columns'] == '本月') :
                    //本月
                    $time_start = Carbon::now()->startOfMonth();//本月第一天的0.00开始
                    $time_end = Carbon::now()->endOfMonth();//本月的最后一天的23.59结束
                    //代理总分润
                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                        })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
                case ($params['columns'] == '近三月') :
                    //近三月
                    $time_start = Carbon::now()->subMonth(2)->startOfMonth();//近三个月的第一天0.00开始
                    $time_end = Carbon::tomorrow();//截止到现在的时间的明天结束
                    //代理总利润
                    $benefit = User::join('account_logs', function ($join) {
                        $join->on('users.id', '=', 'account_logs.user_id');
                        })
                        ->where('users.aid','=',$params['id'])
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit'=>fenToYuan($benefit),
                    ];
                    break;
            }
        }
        else{
            //代理总利润
            $benefit = User::agent()
                ->join('account_logs', function ($join) {
                    $join->on('users.id', '=', 'account_logs.user_id');
                })
                ->where('users.aid','=',$params['id'])
                ->sum('amount');
            //旗下代理数量
            $data1= User::agent()->where('aid','=',$params['id'])
                ->where('level','<>','2')
                ->count();
            //旗下代理列表
            $data = User::agent()->where('aid','=',$params['id'])->where('level','<>','2')->get();
            return [
                'status' => true,
                'code' => '200',
                'data' => $data,
                'data1'=>$data1,
                'benefit'=>fenToYuan($benefit),
            ];
        }

    }


}