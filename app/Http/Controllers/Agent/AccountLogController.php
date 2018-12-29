<?php

namespace App\Http\Controllers\Agent;

use App\AccountLog;
use App\Tube;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountLogController extends Controller
{
    public function index(){
        //获取通道,进行循环遍历
        $tube = Tube::Enabled()->get();
        $tube_option = [];
        foreach($tube as $v){
            $tube_option[$v->id] = $v->display;
        }
        unset($v);
        //定义搜索的字段
        $search_items = [
            'no' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '单号',
            ],
            'type' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '账变类型',
                'options' => AccountLog::ACCOUNT_TYPES,
            ],
            'tube_id'=>[
                'type'=>'custom',
                'form'=>'select',
                'label'=>'通道类型',
                'options'=>$tube_option

            ],
            'business' => [
                'type' => 'equal',
                'form' => 'select',
                'label' => '业务类型',
                'options' => AccountLog::BUSINESSES,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $user = User::getAgentAuthUser();

        $data = AccountLog::latest()
            ->search($search_items)
            ->where('user_id', $user->id)
            ->paginate();
        return _view('agent.account_log.index', compact('data'));
    }
}
