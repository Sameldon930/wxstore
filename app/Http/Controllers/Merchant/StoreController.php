<?php

namespace App\Http\Controllers\Merchant;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller
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

        return _view('merchant.store.index', compact('data', 'user'));
    }

    public function create(){
        return _view('merchant.store.create');
    }

    public function edit(Request $request, $id){
        $data = User::store()->findOrFail($id);

        return _view('merchant.store.edit', compact('data'));
    }

    public function update(Request $request, $id){
        $user = User::store()->findOrFail($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        $user->name = $request->get('name');
        $user->save();

        return redirect()->route('merchant.store.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $user = User::getMerchantAuthUser();

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createStore($request->get('name'), $request->get('password'), $user);

        return redirect()->route('merchant.store.index')->with('msg', '添加成功');
    }

    public function show($id){
        $data = User::store()->findOrFail($id);

        return _view('merchant.store.show', compact('data'));
    }

    public function destroy($id){
        User::destroy($id);

        return back()->with('msg', '删除成功');
    }
}
