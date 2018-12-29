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
 * 文章详情
 */
//旗下商户deal_detail.html
class AgentAccountDetail extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     *
     * @var string
     */
    protected $method = 'AgentAccountDetail';

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
        $id = $params['id'];//商户Id
         $page_num = $params['page_num'];
         //获取order的已支付的订单
        $orders = Order::where('orders.user_id','=',$id)->Paid()
            ->whereBetween('orders.created_at',[$time_start,$time_end])
            ->paginate(3, null, null, $page_num);

        $id = $params['id'];//商户Id
        $user_id = $params['aid'];//当前代理Id


        //获取该商户的姓名和头像
        $data = User::select()
            ->where('id','=',$id)->first();


        //获取该商户的总金额
        $account = Order::select()
            ->join('users','orders.user_id','=','users.id')
            ->where('users.id', '=',$id)
            ->where('orders.pay_status','=','2')
            ->sum('real_amount');


        //获取该商户贡献的总分润
        $benefit = SettleLog::select()
            ->where("user_id","=",$id)
            ->get();
        $array1 = $benefit->toArray();
        $array2 = [];//存放account_logs的order_id数组
//            dd($array2);
        for($i=0;$i<count($array1);$i++){
            array_push($array2,$array1[$i]['id']);
        }
        $benefit1 = AccountLog::select()
            ->join('settle_logs','account_logs.order_id','=','settle_logs.id')
            ->where('settle_logs.user_id','=',$id)//等于当前商户的id
            ->whereIn('account_logs.order_id',$array2)//等于存放的数组值
            ->where('settle_logs.user_id','>',0)//user_id不为空
            ->where('account_logs.user_id','=',$user_id)//等于当前登录的代理Id
            ->sum('amount');


        return [
            'status' => true,
            'code' => '200',
            'data' => $data,
            'account' => fenToYuan($account),
            'benefit' => fenToYuan($benefit1),
            'orders' => $orders
        ];

    }


}