<?php
namespace App\ApiServices\InServices\Response;

use App\Order;
use App\SettleLog;
use App\Tube;
use SebastianBergmann\CodeCoverage\Report\Crap4j;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * api测试类
 */
class StoreMonth extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'StoreMonth';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => 'ID缺失',
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

        if ($params['type']==3){
            $time = $params['time'];
            $atime = Carbon::createFromFormat('Y-m', $time)->startOfMonth();
            $btime = Carbon::createFromFormat('Y-m', $time)->endOfMonth();
//            dd($time);
            $month_number = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$atime,$btime])
                ->where('orders.pay_status','=','2')->count();
            //本月收益总和
            $month_money = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$atime,$btime])
                ->where('orders.pay_status','=','2')->sum('trade_amount');

            //本月收益均价
            $month_avg = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$atime,$btime])
                ->where('orders.pay_status','=','2')->avg('trade_amount');
//        dd($month_avg);
            //本月收益微信通道占比
            $wechat = Order::select()
                ->where('user_id','=',$params['id'])
                ->join('channels','orders.channel_id','=','channels.id')
                ->join('tubes','channels.tube_id','=','tubes.id')
                ->whereBetween('orders.created_at', [$atime,$btime])
                ->where('orders.pay_status','=','2')
                ->where('tubes.id','=','1')
                ->count();
            //本月收益支付宝通道占比
            $pay = Order::select()
                ->where('user_id','=',$params['id'])
                ->join('channels','orders.channel_id','=','channels.id')
                ->join('tubes','channels.tube_id','=','tubes.id')
                ->whereBetween('orders.created_at', [$atime,$btime])
                ->where('orders.pay_status','=','2')
                ->where('tubes.id','=','2')
                ->count();

            $id = $params['id'];
            //查询这个月所有金额的具体分布明细
            $tj = " SELECT COUNT(CASE WHEN IFNULL(trade_amount,0) >=100.00 AND IFNULL(trade_amount,0)<=1000.00 THEN a.id END ) AS '1~10',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=1100.00 AND IFNULL(trade_amount,0)<=5000.00 THEN a.id END ) AS '11~50',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=5100 AND IFNULL(trade_amount,0)<=10000 THEN a.id END ) AS '51~100',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=10100.00 AND IFNULL(trade_amount,0)<=50000.00 THEN a.id END ) AS '101~500',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=50100.00  THEN a.id END ) AS '500+'
	                     FROM orders AS a  WHERE user_id = $id AND pay_status = '2' AND created_at >= '$atime' and created_at<='$btime' " ;
        $month =  DB::select(DB::raw($tj));
//        dd($month);
            return [
                'status' => true,
                'code' => '200',
                'month_number' => $month_number,//月交易笔数
                'month_money'=> fenToYuan($month_money),//月交易金额
                'month_avg'=>fenToYuan($month_avg),//月交易均价
                'wechat'=>$wechat,//微信占比
                'pay'=>$pay,//支付宝占比
                'month'=>$month//月交易金额大小分布
            ];
        }
        else{
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $today = Carbon::today();
            //今日交易金额
            $dataToday = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereDate('orders.created_at', $today)
                ->where('orders.pay_status','=','2')
                ->sum('trade_amount');

            //本月交易笔数
            $month_number = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$startOfMonth,$endOfMonth])
                ->where('orders.pay_status','=','2')->count();

            //本月收益总和
            $month_money = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$startOfMonth,$endOfMonth])
                ->where('orders.pay_status','=','2')->sum('trade_amount');


            //本月收益均价
            $month_avg = Order::select()
                ->where('user_id','=',$params['id'])
                ->whereBetween('orders.created_at', [$startOfMonth,$endOfMonth])
                ->where('orders.pay_status','=','2')->avg('trade_amount');
//        dd($month_avg);
            //本月收益微信通道占比
            $wechat = Order::select()
                ->where('user_id','=',$params['id'])
                ->join('channels','orders.channel_id','=','channels.id')
                ->join('tubes','channels.tube_id','=','tubes.id')
                ->whereBetween('orders.created_at', [$startOfMonth,$endOfMonth])
                ->where('orders.pay_status','=','2')
                ->where('tubes.id','=','1')
                ->count();
            //本月收益支付宝通道占比
            $pay = Order::select()
                ->where('user_id','=',$params['id'])
                ->join('channels','orders.channel_id','=','channels.id')
                ->join('tubes','channels.tube_id','=','tubes.id')
                ->whereBetween('orders.created_at', [$startOfMonth,$endOfMonth])
                ->where('orders.pay_status','=','2')
                ->where('tubes.id','=','2')
                ->count();

            $id = $params['id'];
            //查询这个月所有金额的具体分布明细
            $tj = " SELECT COUNT(CASE WHEN IFNULL(trade_amount,0) >=100.00 AND IFNULL(trade_amount,0)<=1000.00 THEN a.id END ) AS '1~10',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=1100.00 AND IFNULL(trade_amount,0)<=5000.00 THEN a.id END ) AS '11~50',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=5100 AND IFNULL(trade_amount,0)<=10000 THEN a.id END ) AS '51~100',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=10100.00 AND IFNULL(trade_amount,0)<=50000.00 THEN a.id END ) AS '101~500',
		                       COUNT(CASE WHEN IFNULL(trade_amount,0) >=50100.00  THEN a.id END ) AS '500+'
	                     FROM orders AS a  WHERE user_id = $id AND DATE_FORMAT(created_at,'%Y%m') = DATE_FORMAT( CURDATE( ) ,'%Y%m') AND pay_status = '2'" ;
            $month =  DB::select(DB::raw($tj));
            return [
                'status' => true,
                'code' => '200',
                'month_number' => $month_number,//月交易笔数
                'month_money'=> fenToYuan($month_money),//月交易金额
                'month_avg'=>fenToYuan($month_avg),//月交易均价
                'wechat'=>$wechat,//微信占比
                'pay'=>$pay,//支付宝占比
                'dataToday'=>fenToYuan($dataToday),//今日交易量
                'month'=>$month//月交易金额大小分布
            ];
        };

    }


}