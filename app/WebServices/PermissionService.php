<?php
namespace App\Services\WebServices;

use App\AdminLinks;
use App\Http\Controllers\Channel\ChannelCommonController;
use App\PayrollOrder;
use App\SettleAccountLog;
use App\UserInfoCheck;
use App\Withdrawal;
use App\WithdrawalBatch;

class PermissionService{

    public static function getIgnorePermissionGroups(){
        return [
            'login', 'upload_to_tester', 'logout', 'index'
        ];
    }

    public static function getIgnorePermissions(){
        return [
            'admin.dashboard.index', 'admin.upload_to_tester', 'admin.login', 'admin.index', 'admin.logout',
        ];
    }

    public static function getIgnoreGroups(){
        return [
            'dashboard', 'index'
        ];
    }

    public static function getPermissionGroupsMap(){
        return [
            'agent' => '代理管理',
            'merchant' => '商户管理',
            'transaction' => '交易管理',
            'account' => '账变管理',
            'finance' => '财务管理',
            'tube' => '通道管理',
            'system' => '系统管理',
        ];
    }

    public static function isIgnored($permission){
        return in_array($permission, self::getIgnorePermissions());
    }

    public static function getAdminRoutesGroups()
    {
        // 获取组名映射表
        $groups_map = self::getPermissionGroupsMap();

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
}