<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Http\Controllers\Controller;
use App\SettleLog;
use App\Tube;
use App\Order;
use Carbon\Carbon;

class MerchantSettleController extends Controller
{
    public function index(){
        $obj_tubes = Tube::get();
        $tubes = [];
        foreach ($obj_tubes as $tube) {
            $tubes[$tube->id] = $tube->display;
        }

        $search_items = [
            'mobile' => [
                'type' => 'custom',
                'form' => 'text',
                'label' => '账户/店铺名',
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
        ];

        $data = SettleLog::latest()
            ->merchant()
            ->search($search_items)
            ->paginate()
        ;

        return _view('admin.merchant_settle.index', compact('data'));
    }

    public function detail($id)
    {
        $settleLog = SettleLog::with('tube')->find($id);

        $orders = Order::PaidDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->with('channel')
            ->get();

        return _view('admin.merchant_settle.detail', compact('settleLog', 'orders'));
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

        return _view('admin.merchant_settle.sub_orders', compact('orders'));
    }

    public function settle($id)
    {
        //todo 增加判断，防止重复结算
        $settleLog = SettleLog::merchant()->with('tube')->find($id);
        if ($settleLog->status === SettleLog::STATUS_SETTLED) {
            return back()->withErrors('已经结算，无法重复操作！');
        }
        $settleLog->settleForMerchant();

        ActionLog::log(ActionLog::TYPE_SETTLE, $settleLog, "商户结算（{$settleLog->id}）");

        return back()->with('msg', '结算成功');
    }

    public function batchSettle(){
        $settleLogs = SettleLog::merchant()->whereDate('created_at', Carbon::today())->with('tube')->get();

        foreach ($settleLogs as $settleLog){
            if ($settleLog->isWaiting()){
                $settleLog->settleForMerchant();
            }
        }

        ActionLog::log(ActionLog::TYPE_SETTLE, $settleLogs, "批量商户结算");

        return back()->with('msg', '批量结算成功');
    }
}
