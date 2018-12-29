<?php

namespace App\Http\Controllers\Agent;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
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
            'level' => [
                'type' => 'custom',
                'form' => 'select',
                'label' => '审核状态',
                'options' => \App\User::LEVEL_MERCHANTS,
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = User::merchant()
            ->where('aid', $user->id)
            ->search($search_items)
            ->latest()
            ->paginate();

        return _view('agent.merchant.index', compact('data', 'user'));
    }

    public function create(){
        $user = User::getAgentAuthUser();
        if ($user->isCheckedMerchant()){
            return _view('agent.merchant.create');
        }else {
            return _view('agent.merchant.info_check_tip');
        }

    }

    public function edit(Request $request, $id){
        $data = User::merchant()->findOrFail($id);

        return _view('agent.merchant.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::merchant()->findOrFail($id);

        $this->validate($request, [
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'name' => 'required',
        ]);

        $user->mobile = $request->get('mobile');
        $user->name = $request->get('name');
        $user->save();

        return redirect()->route('agent.merchant.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $user = User::getAgentAuthUser();

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createStore($request->get('name'), $request->get('password'), $user);

        return redirect()->route('agent.merchant.index')->with('msg', 'tu成功');
    }

    public function show($id){
        $data = User::merchant()->findOrFail($id);

        return _view('agent.merchant.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }
}
