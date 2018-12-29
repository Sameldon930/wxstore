<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23
 * Time: 16:35
 */

namespace App\ApiServices\InServices\Response;

use App\Order;
use App\User;
use Validator;
use Carbon\Carbon;
//谢树文
//交易查询
class TransactionDetails extends BaseResponse implements InterfaceResponse
{
    /**
     * 接口名称
     * @var string
     */
    protected $method = 'TransactionDetails';

    /**
     * 接口参数检验
     */

    public function checkParams(&$params)
    {

        $rules = [
            'id' => 'required|min:1'
        ];
        $messages = [
            'id.required' => 'id缺失',
            'id.min' => 'id最少1个字符'
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
        $page_num = $params['page_num'];
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
            $sql = Order::select('body', 'order_no', 'orders.created_at', 'trade_amount', 'orders.pay_status', 'tube_id')
                ->join('channels', 'orders.channel_id', '=', 'channels.id')
                ->where('user_id', '=', $id);
        }else{
            array_push($store_id,$id);
            $sql =Order::select('body', 'order_no', 'orders.created_at', 'trade_amount', 'orders.pay_status', 'tube_id')
                ->join('channels', 'orders.channel_id', '=', 'channels.id')

                ->wherein('user_id',$store_id);

        }
        $arr =[];
        if (!empty($params['order'])){
            $arr = array_merge(array('orders.order_no'=>$params['order']),$arr);
        }
        if (!empty($params['store'])){
            if ($params['store']!=1){
                $store = Order::select()->where('user_id',$params['store'])->value('body');
                if ($store==null){
                    return[
                        'status' => true,
                        'code' => '2001',
                        'msg' => '暂无数据'
                    ];
                }
                $arr = array_merge(array('body'=>$store),$arr);

                $arr = array_merge(array('user_id'=>$params['store']),$arr);

            }
        }
        if (!empty($params['channel'])){
            if ($params['channel'] != 3) {
                $arr = array_merge(array('channels.tube_id'=>$params['channel']),$arr);
            }
        }
        if (!empty($params['state'])){
            if ($params['state'] != 3) {
                $arr = array_merge(array('orders.pay_status'=>$params['state']),$arr);
            }
        }
        $now = Carbon::now()->toDateString();
        if (!empty($params['starttime']) && !empty($params['endtime'])) {
            $adate =  $params['starttime'];
            $bdate = $params['endtime'];
            $atime = Carbon::createFromFormat('Y-m-d', $adate)->startOfDay();
            $nobtime = Carbon::createFromFormat('Y-m-d', $adate)->addMonthsNoOverflow(3);
            $btime = Carbon::createFromFormat('Y-m-d', $bdate)->endOfDay();
            $sql->whereBetween('orders.created_at', [$atime, $btime]);
        }
        if (!empty($params['starttime'])&&empty($params['endtime'])){
            $adate =  $params['starttime'];
            $atime = Carbon::createFromFormat('Y-m-d', $adate)->startOfDay();
            $nobtime = Carbon::createFromFormat('Y-m-d', $adate)->addMonthsNoOverflow(3);
            $sql->whereBetween('orders.created_at', [$atime, $nobtime]);
        }
         $last = $sql->where($arr)->orderBy('orders.created_at', 'desc')->paginate(3, null, null, $page_num);
        foreach ($last as $value) {
            $value->pay_status = Order::PAY_STATUS[$value->pay_status];
        }
        unset($value);
                $count = $sql->where($arr)->count();
                $sum = $sql->where($arr)->sum('trade_amount');
            return [
                'status' => true,
                'code' => '200',
                'data' => $last??'',
                'count' => $count ?? '',
                'sum' => $sum ?? '',
                'store' => $store ?? '',
                'merchant' => $merchant ?? '',
            ];
        }

}