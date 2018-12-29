<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Role;
use App\Services\WebServices\PermissionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /* 角色管理 */
    public function index()
    {
        $data = Role::latest()->get();

        return _view('admin.role.index', compact('data'));
    }

    public function create()
    {
        $routes_groups = PermissionService::getAdminRoutesGroups();

        return _view('admin.role.create', compact('routes_groups'));
    }

    public function store(Request $request)
    {
        $inputs = $request->all();

        $rules = [
            'name' => 'required|unique:roles,name',
            'display' => 'required',
            'permissions' => 'required',
        ];

        $this->validate($request, $rules);
        $role = Role::create([
            'name' => $inputs['name'],
            'display' => $inputs['display'],
        ]);

        $role->permissions()->sync($inputs['permissions']);

        // admin_action_logs($role->toArray(), "{$this->getUser()->mobile}：添加角色编号（{$role->id}）");

        return redirect(route('admin.role.index'))->with('msg', '角色添加成功');
    }

    public function edit($id)
    {
        $routes_groups = PermissionService::getAdminRoutesGroups();
        $role = Role::findOrFail($id);
        $permissions = $role->pivots()->pluck('permission_id')->all();

        return _view('admin.role.edit', compact('role', 'routes_groups', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $inputs = $request->all();

        $rules = [
            'name' => 'required|unique:roles,name,' . $role->id,
            'display' => 'required',
            'permissions' => 'required',
        ];

        $this->validate($request, $rules);

        $role->name = $inputs['name'];
        $role->display = $inputs['display'];
        $role->save();
        $role->permissions()->sync($inputs['permissions'] ?? []);

        // admin_action_logs($role->toArray(), "{$this->getUser()->mobile}：编辑角色编号（{$id}）");

        return redirect(route('admin.role.index'))->with('msg', '角色编辑成功');
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->permissions()->detach();
        $role->forceDelete();

        // admin_action_logs($role->toArray(), "{$this->getUser()->mobile}：删除角色编号（{$id}）");

        return back()->with('msg', '角色删除成功');
    }

    private function getAdminRoutesGroups()
    {
        // 获取组名映射表
        $groups_map = PermissionService::getPermissionGroupsMap();

        // 获取或有路由
        $all_routes = app()['router']->getRoutes()->getRoutesByName();

        // 过滤总后台路由
        $admin_routes = array_filter($all_routes, function ($route) {
            return $route->getPrefix() === 'admin';
        });

        $routes_groups = [];


        // 按模块分组
        foreach ($admin_routes as $route) {
            $group = $route->action['group'] ?? false;

            // 过滤指定组
            if ($group && array_key_exists($group, $groups_map)) {
                $routes_groups[$group][] = $route;
            }
        }

        return $routes_groups;
    }
    /* 角色管理 */
}
