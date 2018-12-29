<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Http\Controllers\Controller;
use App\Libs\Pay\Bill;
use App\SettleLog;
use App\Tube;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TubeSettleController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function index()
    {
        $obj_tubes = Tube::get();
        $tubes = [];
        foreach ($obj_tubes as $tube) {
            $tubes[$tube->id] = $tube->display;
        }

        $search_items = [
            /*'mobile' => [
                'type' => 'custom',
                'form' => 'text',
                'label' => '账户',
            ],*/
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
        $sql = SettleLog::latest()->Tube()
            ->search($search_items);
        $data = $sql->export();

        $table[]=[
            'ID', '结算号',
            '结算状态', '通道类型',
            '总金额', '实际金额',
            '退款金额', '分润金额',
            '创建时间'
        ];
        $status=SettleLog::STATUS;
        foreach($data as $v){
            $table[]=[
                $v->id,
                $v->settle_no,
                $status[$v->status],
                $v->tube->display,
                fenToYuan($v->total_amount),
                fenToYuan($v->real_amount),
                fenToYuan($v->refund_amount),
                fenToYuan($v->charge_amount),
                $v->created_at,
            ];
        }
        if($this->request->get('export')){
            $title ='通道结算单';
            $msg =export_data($table, $title);
            return back()->with('msg',$msg);
        }

        return _view('admin.tube_settle.index', compact('data','table'));
    }

    public function detail($id)
    {
        $settleLog = SettleLog::with('tube')->find($id);

        $orders = Order::createDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->with('channel')
            ->get();

        $count = Order::createDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->with('channel')
            ->Paid()
            ->count();
        return _view('admin.tube_settle.detail', compact('settleLog', 'orders','count'));
    }

    public function subOrders(SettleLog $settleLog)
    {
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
            ->with('channel')
            ->search($search_items)
            ->paginate(10);

        return _view('admin.tube_settle.sub_orders', compact('orders'));
    }

    public function settle($id)
    {
        //todo 增加判断，防止重复结算
        $settleLog = SettleLog::with('tube')->find($id);
        if ($settleLog->status === SettleLog::STATUS_SETTLED) {
            return back()->withErrors('已经结算，无法重复操作！');
        }
        $settleLog->settleForTube();

        ActionLog::log(ActionLog::TYPE_SETTLE, $settleLog, "通道结算（{$settleLog->id}）");

        return back()->with('msg', '结算成功');
    }

    public function downloadBill($id)
    {
        $settleLog = SettleLog::with('tube')->find($id);

        $users = Order::createDate($settleLog->created_at->subDay())
            ->tube($settleLog->tube_id)
            ->get()
            ->groupBy('user_id')
            ->keys();


        $tube = $settleLog->tube->name;
        $date = $settleLog->getSettleOrderDate();

        //ndd($tube);
       // ndd($date);
       // dd($users);
        $bill = new Bill(['tube' => $tube, 'date' => $date, 'users' => $users]);
        $filePath = $bill->run();

        ActionLog::log(ActionLog::TYPE_SETTLE, $settleLog, "下载结算单（{$filePath}）");
        return response()->download($filePath);

    }
}
