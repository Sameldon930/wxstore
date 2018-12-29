<?php

namespace App\Http\Controllers\Agent;

use App\Channel;
use App\Exceptions\ErrorMessageException;
use App\Http\Controllers\Controller;
use App\User;
use App\UserAgentChannel;
use App\UserAgentInfo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class AgentController extends Controller
{
    public function index(){
        $user = User::getAgentAuthUser();

        $search_items = [
            'name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '姓名',
            ],
            'mobile' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '商户号',
            ],
            'level'=>[
                'type'=>'custom',
                'form'=>'select',
                'label'=>'审核状态',
                'options'=>\App\User::LEVEL_AGENTS
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = User::agent()
            ->where('aid', $user->id)
            ->latest()
            ->search($search_items)
            ->paginate()
        ;
        
        return _view('agent.agent.index', compact('data'));
    }

    public function create(){
        $user = User::getAgentAuthUser();
        if ($user->isCheckedAgent()){
            return _view('agent.agent.create');
        }else {
            $userAgentInfo = UserAgentInfo::where('user_id', $user->id)->first();
            return _view('agent.agent.info_check_tip', compact('userAgentInfo'));
        }

    }

    public function edit(Request $request, $id){
        $data = User::agent()->with('user_agent_channels.channel')->findOrFail($id);

        return _view('agent.agent.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::agent()
            ->with('user_agent_channels.channel')
            ->with('a_user.user_agent_channels.channel')
            ->findOrFail($id);

        $this->validate($request, [
            /*'mobile' => 'required|unique:users,mobile,' . $user->id,*/
            'name' => 'required',
        ]);

        UserAgentChannel::updateUserChannel($user, $request);

        $user->name = $request->get('name');
        $user->save();

        return back()->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createAgent($request->get('name'), $request->get('password'), User::getAgentAuthUser());

        return redirect()->route('agent.agent.index')->with('msg', '添加成功');
    }

    public function show($id){
        $data = User::agent()->findOrFail($id);

        return _view('agent.agent.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }

}