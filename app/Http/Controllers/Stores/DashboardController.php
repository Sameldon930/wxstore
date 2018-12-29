<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class DashboardController extends Controller
{
    public function index(){

//        echo '这里是门店首页！';
//        die;
        $data = [
            'today_order' => 0,
            'today_paid_amount' => 0,
            'today_trade_amount' => 0,

            'monthly_order' => 0,
            'monthly_paid_amount' => 0,
            'monthly_trade_amount' => 0,

            'total_order' => 0,
            'total_paid_amount' => 0,
            'total_trade_amount' => 0,

            'total_member' => 0,
            'total_store' => 0,

            'total_agent' => 0,
            'total_merchant' => 0,
            'total_checked_agent' => 0,
            'total_checked_merchant' => 0,

            'today_agent' => 0,
            'today_merchant' => 0,
            'today_checked_agent' => 0,
            'today_checked_merchant' => 0,
        ];
        $user = User::getStoreAuthUser();
//        dd($user);
        $orders = $user->orders;
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 交易统计
        foreach ($orders as $order){
            $isPaid = $order->isPaid();

            $data['total_order']++;
            $data['total_trade_amount'] += $order->trade_amount;
            $isPaid && $data['total_paid_amount']+= $order->trade_amount;;

            if ($order->created_at->between($today, $tomorrow)){
                $data['today_order']++;
                $data['today_trade_amount'] += $order->trade_amount;
                $isPaid && $data['today_paid_amount']+= $order->trade_amount;;
            }

            if ($order->created_at->between($startOfMonth, $tomorrow)){
                $data['monthly_order']++;
                $data['monthly_trade_amount'] += $order->trade_amount;
                $isPaid && $data['monthly_paid_amount']+= $order->trade_amount;;
            }
        }

        $data['total_trade_amount'] = fenToYuan($data['total_trade_amount']);
        $data['total_paid_amount'] = fenToYuan($data['total_paid_amount']);
        $data['today_trade_amount'] = fenToYuan($data['today_trade_amount']);
        $data['today_paid_amount'] = fenToYuan($data['today_paid_amount']);
        $data['monthly_trade_amount'] = fenToYuan($data['monthly_trade_amount']);
        $data['monthly_paid_amount'] = fenToYuan($data['monthly_paid_amount']);

        return _view('stores.dashboard.index',compact('data', 'user'));
    }
}