<?php

namespace App\Http\Controllers\Agent;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
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
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = User::store()
            ->where('aid', $user->id)
            ->latest()
            ->search($search_items)
            ->paginate()
        ;

        return _view('agent.store.index', compact('data', 'user'));
    }

    public function create(){
        return _view('agent.store.create');
    }

    public function edit(Request $request, $id){
        $data = User::store()->findOrFail($id);

        return _view('agent.store.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::store()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $user->name = $request->get('name');
        $user->save();

        return redirect()->route('agent.store.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $user = User::getAgentAuthUser();

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createStore($request->get('name'), $request->get('password'), $user);

        return redirect()->route('agent.store.index')->with('msg', '添加成功');
    }

    public function show($id){
        $data = User::store()->findOrFail($id);

        return _view('agent.store.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }
}
