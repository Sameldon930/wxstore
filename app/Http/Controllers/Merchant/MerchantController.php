<?php

namespace App\Http\Controllers\Merchant;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerchantController extends Controller
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
            ->latest()
            ->search($search_items)
            ->paginate();

        return _view('merchant.merchant.index', compact('data', 'user'));
    }

    public function create(){
        $user = User::getMerchantAuthUser();
        if ($user->isCheckedMerchant()){
            return _view('merchant.merchant.create');
        }else {
            return _view('merchant.merchant.info_check_tip');
        }

    }

    public function edit(Request $request, $id){
        $data = User::merchant()->findOrFail($id);

        return _view('merchant.merchant.edit', compact('data'));
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

        return redirect()->route('merchant.merchant.index')->with('msg', '修改成功');
    }

    public function store(Request $request){

        $user = User::getMerchantAuthUser();

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'name' => 'required',
        ]);

        User::createStore($request->get('name'), $request->get('password'), $user);

        return redirect()->route('merchant.merchant.index')->with('msg', '创建成功');
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
