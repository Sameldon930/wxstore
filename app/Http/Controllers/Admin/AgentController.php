<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Channel;
use App\Http\Controllers\Controller;
use App\Order;
use App\Scopes\StatusScope;
use App\Services\WebServices\StatusSwitchService;
use App\User;
use App\UserAgentChannel;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class AgentController extends Controller
{
    public function index()
    {

        $search_items = [
            'name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '姓名',
            ],
            'mobile' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '账号',
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = User::agent()
            ->latest()
            ->with('a_user')
            ->search($search_items)
            ->paginate();

        return _view('admin.agent.index', compact('data'));
    }

    public function create()
    {
        return _view('admin.agent.create');
    }

    public function edit(Request $request, $id)
    {
        $user = User::agent()->with('user_agent_channels.channel')->findOrFail($id);
        $user_agent_channels = $user->user_agent_channels->toArray();
        $data = [];
        foreach ($user_agent_channels as $v) {
            $data[$v['channel_id']] = $v;
        }

        $channels = Channel::all();
        return _view('admin.agent.edit', compact('user','data', 'channels'));
    }

    public function update(Request $request, $id)
    {

        $user = User::agent()
            ->with('user_agent_channels.channel')
            ->with('a_user.user_agent_channels.channel')
            ->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        UserAgentChannel::updateUserChannel($user, $request);

        $user->name = $request->get('name');
        $user->save();

        ActionLog::log(ActionLog::TYPE_USER, $user, "更新代理（{$user->id}）");

        return back()->with('msg', '修改成功');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        $aUser = User::getAgentUserByMobile($request->get('a_mobile'));

        $user = User::createAgent($request->get('name'), $request->get('password'), $aUser);

        ActionLog::log(ActionLog::TYPE_USER, $user, "添加代理（{$user->id}）");

        return redirect()->route('admin.agent.index')->with('msg', '代理添加成功');
    }

    public function show($id)
    {
        $data = User::agent()->findOrFail($id);

        return _view('admin.agent.show', compact('data'));
    }

    public function destroy($id)
    {

        $user = User::find($id);
        $user->delete();

        ActionLog::log(ActionLog::TYPE_USER, $user, "删除代理（{$user->id}）");

        return back()->with('msg', '删除成功');
    }

    public function switchStatus(Request $request, $id)
    {
        return StatusSwitchService::change(User::class, $id, $request->get('status'));
    }

}