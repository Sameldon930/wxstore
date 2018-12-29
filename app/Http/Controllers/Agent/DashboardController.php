<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class DashboardController extends Controller
{
    public function index(){

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

          $user = User::getAgentAuthUser();

         $agent = User::agent()
            ->where('aid', $user->id)->CheckedAgent()->get();
         $arr = [];
         foreach ($agent as $value){
            array_push($arr,$value->id);
            $merchant = User::merchant()
                ->where('aid', $value->id)->CheckedMerchant()->get();
            foreach ($merchant as $value){
                array_push($arr,$value->id);
                $store = User::store()
                    ->where('aid', $value->id)->get();
                foreach ($store as $value){
                    array_push($arr,$value->id);

                }
            }
        }
        $orders = Order::wherein('user_id',$arr)->Paid()->get();

        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 成员统计
        $subUsers = User::where('aid', $user->id)->get();

        foreach ($subUsers as $subUser){
            $isMerchant = $subUser->isMerchant();

            $isAgent = $subUser->isAgent();
            $isStore = $subUser->isStore();
            $isCheckedMerchant = $subUser->isCheckedMerchant();
            $isCheckedAgent = $subUser->isCheckedAgent();

            $data['total_member']++;

            $isMerchant && $data['total_merchant']++;
            $isAgent && $data['total_agent']++;
            $isStore && $data['total_store']++;

            $isCheckedMerchant && $data['total_checked_merchant']++;
            $isCheckedAgent && $data['total_checked_agent']++;

            if ($subUser->created_at->between($today, $tomorrow)){
                $isMerchant && $data['today_merchant']++;
                $isAgent && $data['today_agent']++;
            }

            if ($isCheckedMerchant && $subUser->user_merchant_info){
                if ($subUser->user_merchant_info->created_at->between($today, $tomorrow)){
                    $data['today_checked_merchant']++;
                }
            }
            if ($isCheckedAgent && $subUser->user_agent_info){
                if ($subUser->user_agent_info->created_at->between($today, $tomorrow)){
                    $data['today_checked_agent']++;
                }
            }
        }

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

        return _view('agent.dashboard.index', compact('data', 'user'));
    }


}