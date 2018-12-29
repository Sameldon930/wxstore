<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ErrorMessageException;
use App\AccountLog;
use App\Http\Controllers\Controller;
use App\Tube;
use Illuminate\Http\Request;


class AccountLogController extends Controller
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * 账变列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        #获取通道数组，用于表单筛选 start
        $tube=Tube::Enabled()->get();
        $tube_option=[];
        foreach($tube as $v){
            $tube_option[$v->id]=$v->display;
        }
        #获取通道数组，用于表单筛选 end

        $search_items = [
            'no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '单号',
            ],
            'mobile' => [
                'type' => 'custom',
                'form' => 'text',
                'label' => '账号',
            ],
            'tube_id' => [
                'type' => 'custom',
                'form' => 'select',
                'label' => '通道类型',
                'options' => $tube_option,
            ],
            'type' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '账变类型',
                'options' => AccountLog::ACCOUNT_TYPES,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];
        $sql = AccountLog::latest()
            ->search($search_items)
            ->orderby('id','desc');

        $data1 = $sql->export();//导出
        $data2 = $sql->sum('amount');//算出账变金额的总和
        $money = fenToYuan($data2);
        $data = AccountLog::latest()
            ->search($search_items)
            ->orderby('id','desc')
            ->paginate()
        ;
//        dd($data->toArray());
        $table[]=[
            'ID','单号','账号','通道类型','账变金额','剩余金额','账变类型','创建时间'
        ];
        $type1 = AccountLog::WITHDRAWAL_TYPES;//提现-账户余额提现 3 和 提现-拒绝账户余额提现 4
        $type2 = AccountLog::SETTLE_TYPES;//结算-子商户交易分润 1  和  结算-商户交易分润 2
        foreach($data1 as $v){
            $table[] =[
                $v->id ??'' ,
                $v->no ??'',
                $v->user->name ??'未知',
                $v->settle_order->tube->display ??'',
                fenToYuan($v->amount)??'',
                fenToYuan($v->balance)??'',
                $type1[$v->type]??$type2[$v->type],
                $v->created_at??'',
            ];
        }
        if($this->request->get('export')){
            $title = '账变列表';
            $msg = export_data($table,$title);
            return back()->with('msg',$msg);
        }


        return _view('admin.account_log.index', compact('data','table','money'));
    }
}
