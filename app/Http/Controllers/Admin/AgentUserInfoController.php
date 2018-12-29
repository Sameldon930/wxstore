<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\User;
use App\UserAgentInfo;
use App\UserInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentUserInfoController extends Controller
{

    public function index(){

        $search_items = [
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = UserAgentInfo::latest()
            ->with('user')
            ->Unchecked()
            ->search($search_items)
            ->paginate()
        ;

        return _view('admin.agent_user_info.index', compact('data'));
    }

    public function show($id){
        $data = UserAgentInfo::with('user')
            ->findOrFail($id)
        ;

        return _view('admin.agent_user_info.show', compact('data'));
    }

    public function pass(Request $request, $id){
        $user = User::enabled()->with('user_agent_info')->findOrFail($id);

        User::becomeCheckedAgent($user);

        ActionLog::log(ActionLog::TYPE_USER, $user, "通过代理审核（{$user->id}）");

        return redirect()->route('admin.agent_user_info.index')->with('msg', '代理审核成功');
    }

    public function reject(Request $request, $id){

        $data = UserAgentInfo::with('user')
            ->findOrFail($id);

        $rejectReason = $request->get('reject_reason');

        $data->reject_reason = $rejectReason;
        $data->status = UserAgentInfo::REJECTED;
        $data->save();

        ActionLog::log(ActionLog::TYPE_USER, $data, "拒绝代理审核（{$data->id}）");

        return redirect()->route('admin.agent_user_info.index')->with('msg', '代理审核拒绝成功');
    }

}
