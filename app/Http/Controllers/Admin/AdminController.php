<?php

namespace App\Http\Controllers\Admin;

use App\ActionLog;
use App\Admin;
use App\Exceptions\ErrorMessageException;
use App\Role;
use App\Services\WebServices\PermissionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function index(){

        $search_items = [
            'name' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '姓名',
            ],
            'mobile' => [
                'type' => 'like',
                'form' => 'text',
                'label' => '手机号',
            ],
            'created_at' => [
                'type' => 'date',
            ],
        ];

        $data = Admin::latest()
            ->search($search_items)
            ->paginate()
        ;
        $roles = Role::get();

        return _view('admin.admin.index', compact('data', 'roles'));
    }

    public function edit(Request $request, $id){
        $data = Admin::with('roles')->findOrFail($id);

        $roles = Role::get();

        return _view('admin.admin.edit', compact('data', 'roles'));
    }

    public function update(Request $request, $id){
        $admin = Admin::findOrFail($id);

        $this->validate($request, [
            'mobile' => 'required|unique:admins,mobile,' . $admin->id,
            'name' => 'required',
        ]);

        $admin->mobile = $request->get('mobile');
        $admin->name = $request->get('name');
        $admin->save();
        $admin->roles()->sync($request->get('roles'));

        ActionLog::log(ActionLog::TYPE_ADMIN, $admin, "更新管理员（{$admin->id}）");

        return back()->with('msg', '修改成功');
    }

    public function store(Request $request){

        $this->validate($request, [
            'password' => 'required|min:6|max:18',
            'mobile' => 'required|unique:admins,mobile',
            'name' => 'required',
        ]);

        $admin = Admin::create([
            'password' => bcrypt($request->get('password')),
            'mobile' => $request->get('mobile'),
            'name' => $request->get('name'),
        ]);
        $admin->roles()->sync($request->get('roles'));

        ActionLog::log(ActionLog::TYPE_ADMIN, $admin, "添加管理员（{$admin->id}）");

        return back()->with('msg', '添加成功');
    }

    public function show($id){
        $data = Admin::findOrFail($id);

        return _view('admin.admin.show', compact('data'));
    }

    public function destroy($id){
        $admin = Admin::findOrFail($id);

        if($admin->hasRole('admin')){
            throw new ErrorMessageException("不能删除管理员");
        }

        $admin->roles()->detach();
        $admin->delete();

        ActionLog::log(ActionLog::TYPE_ADMIN, $admin, "删除管理员（{$admin->id}）");

        return back()->with('msg', '删除成功');
    }
}
