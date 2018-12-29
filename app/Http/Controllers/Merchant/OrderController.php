<?php

namespace App\Http\Controllers\Merchant;

use App\Channel;
use App\Order;
use App\User;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $obj_channels = Channel::get();
        $user = User::getMerchantAuthUser();

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

//        $data = Order::latest()
//            ->with('user','channel')
//            ->where('user_id', $user->id)
//            ->search($search_items)
//            ->paginate();
        $data = Order::latest()
            ->with('user','channel')
            ->where('user_id', $user->id)
//            ->merchant($user)
            ->search($search_items)
            ->paginate();

        return _view('merchant.order.index', compact('data'));
    }

}
