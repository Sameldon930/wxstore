<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/17
 * Time: 10:57
 */

namespace App\ApiServices\InServices\Response;

//谢树文
//交易分析
use App\Order;
use App\SettleLog;
use App\User;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\DB;
class TradingChart extends BaseResponse implements InterfaceResponse
{
    protected $method = 'TradingChart';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $rules = [
            'id' => 'required'
        ];
        $messages = [
            'id.required' => 'id缺失',
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
        $id = $params['id'];
            $params['type'] = isset($params['type']) ? $params['type'] : 'all'; //时间选择
            switch ($params['type']) {
                //日数据
                case ($params['type'] == 'all'):
                    if ($params['Identification']==1){
                        $start = Carbon::now()->startOfDay();
                        $endOf = Carbon::now()->endOfDay();
                    }else if ($params['Identification']==2){
                        $start = Carbon::now()->startOfWeek();
                        $endOf = Carbon::now()->endOfWeek();
                    }else if ($params['Identification']==3){
                        $start = Carbon::now()->startOfMonth();
                        $endOf = Carbon::now()->endOfMonth();
                    }
                    break;
                case ($params['type'] == 1):
                    $date = $params['date'];
                    // 接前面传来的时间并转换
                    $start = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
                    $endOf = Carbon::createFromFormat('Y-m-d', $date)->endOfDay();
                    break;
                //周数据
                case ($params['type'] == 2):
                    $start = Carbon::now()->startOfWeek();
                    $endOf = Carbon::now()->endOfWeek();
                    break;
                //月数据
                case ($params['type'] == 3):
                    $date = $params['date'];
                    $start = Carbon::createFromFormat('Y-m', $date)->startOfMonth();
                    $endOf = Carbon::createFromFormat('Y-m', $date)->endOfMonth();
                    break;
            }

            $sample_week = Order::select()
            ->where('user_id', '=', $id)
            ->whereBetween('created_at', [$start, $endOf])
            ->Paid();

        //求当周有多少金额

        $id = $params['id'];
        //求旗下的门店
        //求旗下的门店
        $user = User::find($id);
        $store = User::store()
            ->where('aid', $user->id)->get();
//            dd($store->toArray());
        $merchant = User::Merchant()->where('id',$id)->get();
//        dd($merchant->toArray());
        $store_id = array();
        foreach ($store as $value){
            array_push( $store_id,$value->id);
        }
        if (count($store)==0){
            $sample_week = Order::select()
                ->where('user_id', '=', $id)
                ->whereBetween('orders.created_at', [$start, $endOf])
                ->Paid();

        }else{
            array_push($store_id,$id);
            $sample_week = Order::select()
                ->whereIn('user_id',$store_id)
                ->whereBetween('orders.created_at', [$start, $endOf])
                ->Paid();
        }

        //求有多少金额

        $data = $sample_week->sum('trade_amount');
        //求订单的数量
        $count = $sample_week->count('trade_amount');
        //当客单价
        $dataToday = $sample_week->avg('trade_amount');
        $zfb = clone $sample_week;
        //用微信支付的数量
        $wx = $sample_week
            ->join('channels', 'orders.channel_id', '=', 'channels.id')
            ->join('tubes','channels.tube_id','=','tubes.id')
            ->where('tubes.id','=','1')
            ->count();
        //用支付宝支付的数量
        $zfb =$zfb
            ->join('channels', 'orders.channel_id', '=', 'channels.id')
            ->join('tubes','channels.tube_id','=','tubes.id')
            ->where('tubes.id','=','2')
            ->count();
        //查询消费金额的分布情况
        //1-10,10-50,50-100,100-500,500+
        $tj = array();
          array_push($tj,Order::select()->where('trade_amount','>=',100)->where('trade_amount','<',1000)
              ->where('user_id', '=', $id)->Paid()
              ->whereBetween('orders.created_at', [$start, $endOf])
              ->count());
          array_push($tj, Order::select()->where('trade_amount','>=',1000)->where('trade_amount','<',5000)
              ->where('user_id', '=', $id)->Paid()
              ->whereBetween('orders.created_at', [$start, $endOf])
              ->count());
          array_push($tj, Order::select()->where('trade_amount','>=',5000)->where('trade_amount','<',10000)
              ->where('user_id', '=', $id)->Paid()
              ->whereBetween('orders.created_at', [$start, $endOf])
              ->count());
          array_push($tj,Order::select()->where('trade_amount','>=',10000)->where('trade_amount','<',50000)
              ->where('user_id', '=', $id)->Paid()
              ->whereBetween('orders.created_at', [$start, $endOf])
              ->count());
          array_push($tj,Order::select()->where('trade_amount','>=','50000.00')
              ->where('user_id', '=', $id)->Paid()
              ->whereBetween('orders.created_at', [$start, $endOf])
              ->count());

           return [
            'status' => true,
            'code' => '200',
            'data' => fenToYuan($data),
            'count' => $count,
            'zfb' => $zfb,
            'wx' => $wx,
            'section' => $tj,
            'dataToday' => fenToYuan($dataToday),
        ];
    }
}