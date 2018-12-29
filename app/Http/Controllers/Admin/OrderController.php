<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorMessageException;
use App\Libs\Pay\Payment;
use App\Order;
use App\Channel;
use App\SettleLog;
use App\Tube;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * 订单列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $obj_channels = Channel::get();
        $channels = [];
        foreach ($obj_channels as $channel) {
            $channels[$channel->id] = $channel->display;
        }
        $search_items = [

            'order_no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '订单号',
            ],
            'out_order_no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '外部订单号',
            ],
            'channel_id' => [
                'type' => 'custom',
                'form' => 'select',
                'label' => '渠道类型',
                'options' => $channels,
            ],
            'created_at' => [
                'type' => 'date',
            ],
            'pay_status' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '支付状态',
                'options' => Order::PAY_STATUS
            ],
            'user_name'=>[
                'type'=>'custom',
                'form'=>'text',
                'label'=>'商户名称'
            ]
        ];

        $sql = Order::latest()
            ->with('user','channel')
            ->search($search_items);

        $data = $sql->export();
        //所有订单获得的金额
        $calc = Order::latest()
                ->with('user','channel')
                ->search($search_items)
                ->sum('real_amount');
        $money = fenToYuan($calc);

        $table[]=[
            'ID', '平台订单号',
            '外部订单号', '商户名称',
            '交易金额', '实际金额',
            '支付渠道', '支付状态',
            '支付时间', '创建时间',
        ];
        $pay_status=Order::PAY_STATUS;
        foreach($data as $v){
            $table[]=[
                $v->id,
                $v->order_no,
                $v->out_order_no,
                $v->user->name ?? '未知',
                fenToYuan($v->trade_amount),
                fenToYuan($v->real_amount),
                $v->channel->display ?? '未知',
                $pay_status[$v->pay_status],
                $v->paid_at,
                $v->created_at,
            ];
        }

        if($this->request->get('export')){
            $title ='订单列表';
            $msg =export_data($table, $title);
            return back()->with('msg',$msg);
        }

        return _view('admin.order.index', compact('data','table','money'));
    }

    // 模拟生成订单
    public function simulateCreate(Request $request)
    {
        if ($request->get('channel') == 'WECHAT_JS'){
            $params = [
                'merchant_no' => $request->get('mobile'),
                'channel' => $request->get('channel'),
                'amount' => $request->get('amount') * 100,
            ];
            $specificParams = [
                'openid' => 'oHkld1ry74ZqvJI5FxhFIcxlWreM',
            ];

            try {
                $payment = new Payment($params, $specificParams);
                $result = $payment->run();
            }catch (\Exception $e){
                throw new ErrorMessageException($e->getMessage());
            }
        }else {
            $params = [
                'merchant_no' => $request->get('mobile'),
                'channel' => $request->get('channel'),
                'amount' => $request->get('amount') * 100,
                'body' => config('app.name') . '商品',
            ];
            $specificParams = [
                'buyer_id' => '2088702303867765',
            ];
            try {
                $payment = new Payment($params, $specificParams);
                $result = $payment->run();
            }catch (\Exception $e){
                throw new ErrorMessageException($e->getMessage());
            }
        }

        return back()->with('msg', '模拟下单成功')->withInput($request->all());
    }

    // 订单模拟支付
    public function simulatePay(Request $request, $id)
    {
        $order = Order::with('channel.tube')->find($id);

        $order->out_order_no = date('YmdHis');
        $order->paid_at = Carbon::now()->subDay();
        $order->pay_status = Order::PAY_STATUS_PAID;
        $order->save();

        return back()->with('msg', '模拟支付成功')->withInput($request->all());
    }

    // 模拟生成结算单
    public function simulateSettleLog()
    {
        SettleLog::generateSettleLogs();

        return back()->with('msg', '模拟生成结算单成功');
    }
}
