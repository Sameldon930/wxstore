<?php
namespace App\ApiServices\InServices\Response;

use App\Order;
use App\SettleLog;
use App\Side;
use Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * api测试类
 */
class StoreWallet extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'StoreWallet';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {
        $is_login = new LoginCheck;
        if (!$is_login->StoreLogin_verify($params)){
            return ['status' => false, 'code' => '0005', 'msg' => '还未登录'];
        };
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
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        //筛选今日
        $example_today = Order::select()
            ->where('user_id','=',$params['id'])
            ->whereDate('orders.created_at', $today)
            ->where('orders.pay_status','=',2);

        //筛选昨日
        $example_yesterday = Order::select()
            ->where('user_id','=',$params['id'])
            ->whereDate('orders.created_at', $yesterday)
            ->where('orders.pay_status','=',2);

        //今日收益数量
        $today_number = $example_today->count();

        //今日收益总和
        $today_money = $example_today->sum('trade_amount');

        //昨日收益数量
        $yesterday_number = $example_yesterday->count();

        //昨日收益总和
        $yesterday_money = $example_yesterday->sum('trade_amount');

        //首页轮播图
        $side = Side::select('image','url')
                ->where('group_id','=','3')
                ->where('status','=','1')
                ->orderby('orderby','asc')
                ->take(5)
                ->get();
        foreach($side as &$value){
            $value->image = 'storage/serve/'.$value->image;
        }

        return [
            'status' => true,
            'code' => '200',
            'today_number' => $today_number,
            'today_money'=> fenToYuan($today_money),
            'yesterday_number'=> $yesterday_number,
            'yesterday_money'=> fenToYuan($yesterday_money),
            'side'=>$side
        ];
    }


}