<?php

namespace App\Http\Controllers\Agent;

use App\Channel;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreOrderController extends Controller
{
    public function index()
    {
        $obj_channels = Channel::get();
        $user = User::getAgentAuthUser();
        $channels = [];
        foreach ($obj_channels as $channel) {
            $channels[$channel->id] = $channel->name;
        }
        $search_items = [
            'order_no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '订单号',
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
            ]
        ];

        $data = Order::latest()
            ->with('user','channel')
            ->store($user)
            ->search($search_items)
            ->paginate()
        ;

        return _view('agent.store_order.index', compact('data'));
    }
}
