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
class Month extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'month';

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



        $user = User::find($params['id']);
        $store = User::store()
            ->where('aid', $user->id)->get();
//        dd($store->toArray());
        $store_id = array();
        foreach ($store as $value){
            array_push( $store_id,$value->id);
        }
        $sql = Order::select()->Paid();
        if (count($store)==0){
            $sql = $sql->where('user_id', '=', $params['id']);
        }else{
            array_push($store_id,$params['id']);
            $sql =$sql->whereIn('user_id',$store_id);
        }
        $dataToday = clone $sql;
        $today = Carbon::today();
        //今日交易金额
        $dataToday =$dataToday
            ->whereDate('orders.created_at', $today)
            ->sum('trade_amount');
        if ($params['type']==3){
            $time = $params['time'];
            $atime = Carbon::createFromFormat('Y-m', $time)->startOfMonth();
            $btime = Carbon::createFromFormat('Y-m', $time)->endOfMonth();
            $sql->whereBetween('orders.created_at', [$atime,$btime]);
        }else{
            $now = Carbon::now()->toDateString();
            $atime = Carbon::createFromFormat('Y-m-d', $now)->startOfMonth();
            $btime = Carbon::createFromFormat('Y-m-d', $now)->endOfMonth();
            $sql->whereBetween('orders.created_at', [$atime,$btime]);
        }
        $month_number = $sql->count();
        $month_money = $sql->sum('trade_amount');
        $month_avg = $sql->avg('trade_amount');
       $pay =  clone $sql;
        $a=clone $sql;
        $b=clone $sql;
        $c=clone $sql;
        $d=clone $sql;
        $e=clone $sql;
        $wechat = $sql->join('channels','orders.channel_id','=','channels.id')
            ->join('tubes','channels.tube_id','=','tubes.id')->where('tubes.id','=','1')
            ->count();

        $pay = $pay->join('channels','orders.channel_id','=','channels.id')
            ->join('tubes','channels.tube_id','=','tubes.id')->where('tubes.id','=','2')
            ->count();
//        '1~10', '11~50', '51~100', '101~500', '500+'
        $month = array();
      $a = $a->where('trade_amount','>=',100)->where('trade_amount','<',1000)
            ->count();

      array_push($month,$a);
      $b =    $b->where('trade_amount','>=',1000)->where('trade_amount','<',5000)
            ->count();
        array_push($month,$b);
      $c =   $c->where('trade_amount','>=',5000)->where('trade_amount','<',10000)
            ->count();
        array_push($month,$c);
      $d =  $d->where('trade_amount','>=',10000)->where('trade_amount','<',50000)
            ->count();
        array_push($month,$d);
      $e =  $e->where('trade_amount','>=',50000)
            ->count();
        array_push($month,$e);
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
        }

}