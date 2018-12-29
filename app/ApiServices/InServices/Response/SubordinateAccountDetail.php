<?php
namespace App\ApiServices\InServices\Response;

use App\AccountLog;
use App\Article;
use App\Order;
use App\SettleLog;
use App\User;
use App\UserAgentChannel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Validator;

/**
 *
 */
//张泽山
class SubordinateAccountDetail extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'SubordinateAccountDetail';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required',
            'token' => 'required'
        ];
        $messages = [
            'id.required' => 'ID缺失',
            'token.required' => '身份校验失败'
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
            switch ($params['columns']){
                case ($params['columns']=='交易时间'):
                    $id = $params['id'];//商户Id
                    $time_start = '';
                    $time_end = Carbon::tomorrow();
                    //获取该代理贡献的总分润
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
                case ($params['columns']=='今日'):
                    $id = $params['id'];//商户Id
                    //今日
//                    $time_start = '';
                    $time_start = Carbon::now()->startOfDay();//今天的0.00开始
                    $time_end = Carbon::now()->endOfDay();//今天的23.59结束
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
                case ($params['columns']=='近七日'):
                    $id = $params['id'];//商户Id
                    //近七日
                    $time_start = Carbon::now()->startOfDay()->subDay(7);//七天前的0.00
                    $time_end = Carbon::now()->endOfDay();//今天的23.59
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');
                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
                case ($params['columns']=='本周'):
                    $id = $params['id'];//商户Id
                    //本周
                    $time_start =  Carbon::now()->startOfWeek();//周一的0.00开始
                    $time_end = Carbon::now()->endOfWeek();//周日的23.59结束
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');

                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
                case ($params['columns']=='本月'):
                    $id = $params['id'];//商户Id
                    //本月
                    $time_start = Carbon::now()->startOfMonth();//本月第一天的0.00开始
                    $time_end = Carbon::now()->endOfMonth();//本月的最后一天的23.59结束
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');

                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
                case ($params['columns']=='近三月'):
                    $id = $params['id'];//商户Id
                    $time_start = Carbon::now()->subMonth(2)->startOfMonth();//近三个月的第一天0.00开始
                    $time_end = Carbon::tomorrow();//截止到现在的时间的明天结束
                    $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                        ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                        ->join('users','settle_logs.user_id','=','users.id')
                        ->where('account_logs.user_id',$id)
                        ->where('settle_logs.user_id','>',0)
                        ->whereBetween('account_logs.created_at',[$time_start,$time_end])
                        ->sum('amount');

                    return [
                        'status' => true,
                        'code' => '200',
                        'benefit' => fenToYuan($benefit1)
                    ];
                    break;
            }

        }
        else{
            $id = $params['id'];//商户Id


            //获取该代理的姓名和头像
            $data = User::select()
                ->where('id','=',$id)->first();


            //获取该代理贡献的总分润
            $benefit1 =  AccountLog::select('users.name','account_logs.created_at','account_logs.amount','settle_logs.tube_id')
                ->join('settle_logs','settle_logs.id','=','account_logs.order_id')
                ->join('users','settle_logs.user_id','=','users.id')
                ->where('account_logs.user_id',$id)
                ->where('settle_logs.user_id','>',0)
                ->sum('amount');


            return [
                'status' => true,
                'code' => '200',
                'data' => $data,
                'benefit' => fenToYuan($benefit1),
            ];
        }

    }




}