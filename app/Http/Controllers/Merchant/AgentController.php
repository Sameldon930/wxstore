<?php

namespace App\Http\Controllers\Merchant;

use App\Channel;
use App\Exceptions\ErrorMessageException;
use App\Http\Controllers\Controller;
use App\User;
use App\UserAgentChannel;
use App\UserAgentInfo;
use App\UserMerchantInfo;
use App\UserMerchantTube;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class AgentController extends Controller
{
    public function index(){
        $user = User::getMerchantAuthUser();

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
        
        return _view('merchant.merchant.index', compact('data'));
    }

    public function create(){
        $user = User::getMerchantAuthUser();
        if ($user->isCheckedMerchant()){
            return _view('merchant.merchant.create');
        }else {
            $userMerchantInfo = UserMerchantInfo::where('user_id', $user->id)->first();
            return _view('merchant.merchant.info_check_tip', compact('userMerchantInfo'));
        }

    }

    public function edit(Request $request, $id){
        $data = User::merchant()->with('user_agent_tubes.tube')->findOrFail($id);

        return _view('merchant.merchant.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::merchant()
            ->with('user_agent_tubes.tube')
            ->with('a_user.user_agent_tubes.tube')
            ->findOrFail($id);

        $this->validate($request, [
            /*'mobile' => 'required|unique:users,mobile,' . $user->id,*/
            'name' => 'required',
        ]);

        UserMerchantTube::updateUserTube($user, $request);

        $user->name = $request->get('name');
        $user->save();

        return back()->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createMerchant($request->get('name'), $request->get('password'), User::getMerchantAuthUser());

        return redirect()->route('merchant.merchant.index')->with('msg', '添加成功');
    }

    public function show($id){
        $data = User::merchant()->findOrFail($id);

        return _view('merchant.merchant.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }

}