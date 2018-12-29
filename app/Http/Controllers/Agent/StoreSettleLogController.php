<?php

namespace App\Http\Controllers\Agent;

use App\Order;
use App\SettleLog;
use App\Tube;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreSettleLogController extends Controller
{
    public function index()
    {
        $obj_tubes = Tube::get();
        $user = User::getAgentAuthUser();
        $tubes = [];
        foreach ($obj_tubes as $tube) {
            $tubes[$tube->id] = $tube->display;
        }
        $search_items = [
            'settle_no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '结算单号',
            ],
            'tube_id' => [
                'type' => 'custom',
                'form' => 'select',
                'label' => '通道类型',
                'options' => $tubes,
            ],
            'created_at' => [
                'type' => 'date',
            ],
            'status' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '结算状态',
                'options' => SettleLog::STATUS
            ]
        ];

        $data = SettleLog::latest()
            ->with('user','tube')
            ->store($user)
            ->search($search_items)
            ->paginate()
        ;

        return _view('agent.store_settle_log.index', compact('data'));
    }

    public function detail($id)
    {
        $settleLog = SettleLog::with('tube')->find($id);

        $orders = Order::createDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->with('channel')
            ->get()
        ;

        return _view('agent.store_settle_log.detail', compact('settleLog', 'orders'));
    }

    public function subOrders(SettleLog $settleLog){
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
        ];

        $orders = Order::createDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->where('user_id', $settleLog->user_id)
            ->with('channel')
            ->search($search_items)
            ->paginate(10)
        ;

        return _view('agent.store_settle_log.sub_orders', compact('orders'));
    }
}
