<?php

namespace App\Http\Controllers\Admin;

use App\Services\WebServices\StatusSwitchService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{

    public function index(){

        $search_items = [
            'name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '名称',
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
            ->latest()
            ->with('a_user')
            ->search($search_items)
            ->paginate()
        ;

        return _view('admin.store.index', compact('data'));
    }

    public function edit(Request $request, $id){
        $data = User::store()->findOrFail($id);

        return _view('admin.store.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::store()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $user->name = $request->get('name');
        $user->save();

        return back()->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'a_mobile' => 'required',
            'name' => 'required',
        ]);

        $aUser = User::getAgentUserByMobile($request->get('a_mobile'));

        User::createStore($request->get('name'), $request->get('password'), $aUser);

        return redirect()->route('admin.store.index')->with('msg', '商户添加成功');
    }

    public function show($id){
        $data = User::store()->findOrFail($id);

        return _view('admin.store.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }

    public function switchStatus(Request $request, $id){
        return StatusSwitchService::change(User::class, $id, $request->get('status'));
    }
}
