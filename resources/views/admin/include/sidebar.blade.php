<?php
$menus_groups = [
    [
        'name' => 'dashboard.index', 'display' => '数据中心', 'icon' => 'glyphicon glyphicon-stats',
    ],
    [
        'name' => 'agent', 'display' => '代理管理', 'icon' => 'fa fa-user-plus',
        'menus' => [
            ['agent.index', '代理列表'],
            ['agent_user_info.index', '代理信息审核'],
        ],
    ],
    [
        'name' => 'merchant', 'display' => '商户管理', 'icon' => 'fa fa-user',
        'menus' => [
            ['merchant.index', '商户列表'],
            ['store.index', '门店列表'],
             ['merchant_user_info.index', '商户信息审核'],
        ],
    ],
    [
        'name' => 'transaction', 'display' => '交易管理', 'icon' => 'fa fa-bar-chart',
        'menus' => [
            ['order.index', '订单列表'],
            // ['operation_report.index', '经营报表'],
        ],
    ],
    [
        'name' => 'account', 'display' => '账变管理', 'icon' => 'fa fa-table',
        'menus' => [
            ['account_log.index', '账变列表'],
        ],
    ],
    [
        'name'=>'operation','display'=>'运营管理','icon'=>'fa fa-link',
        'menus'=>[
            ['side.index','幻灯片列表'],
            ['message.index','系统消息列表'],
            ['advertising.index','广告推广列表']
        ],
    ],

    [
        'name' => 'finance', 'display' => '财务管理', 'icon' => 'fa fa-table',
        'menus' => [
            ['tube_settle.index', '通道对账单列表'],
            ['merchant_settle.index', '商户对账单列表'],
            ['withdrawal.index', '商户提现列表'],
            ['merchant.account_list', '商户账户余额列表'],
        ],
    ],
    [
        'name' => 'tube', 'display' => '通道管理', 'icon' => 'fa fa-link',
        'menus' => [
            ['tube.index', '通道列表'],
            ['channel.index', '渠道列表'],
        ],
    ],
    [
        'name' => 'system', 'display' => '系统设置', 'icon' => 'fa fa-cog',
        'menus' => [
            ['admin.index', '管理员管理'],
            ['role.index', '角色管理'],
            ['action_log.index', '操作日志'],
            ['meta.index', '系统配置'],
        ],
    ],
];

$user = Auth::user();
$all_permissions = $user->getPermissions()->all();

if (!$user->isAdmin()) {
    foreach ($menus_groups as $group_key => $group) {
        if (isset($group['menus'])) {
            foreach ($group['menus'] as $menu_key => &$menu) {
                $route = "admin.{$menu[0]}";
                if (!in_array($route, $all_permissions)) {
                    unset($menus_groups[$group_key]['menus'][$menu_key]);
                }
            }
            if (count($menus_groups[$group_key]['menus']) === 0){
                unset($menus_groups[$group_key]);
            }
        }
    }
}


?>
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            @foreach($menus_groups as $group)
                <li @if(isset($group['menus'])) class="treeview" @endif id="{{str_replace('.', '_', $group['name'])}}">
                    <a href="@if(isset($group['menus'])) # @else {{ route("admin.{$group['name']}") }} @endif">
                        <i class="{{ $group['icon'] }}"></i> <span>{{ $group['display'] }}</span>
                        @if(isset($group['menus']))
                            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                        @endif
                    </a>
                    @if(isset($group['menus']))
                        <ul class="treeview-menu">
                            @foreach($group['menus'] as $menu)
                                <li id="{{ $group['name'] . '_' . str_replace('.', '_', $menu[0]) }}">
                                    <a href="{{ route("admin.{$menu[0]}") }}"><i class="fa fa-circle-o"></i>{{ $menu[1] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
        {{--<ul class="sidebar-menu" data-widget="tree">
            <li id="index">
                <a href="{{route('admin.dashboard.index')}}">
                    <i class="fa fa-dashboard"></i> <span>数据中心</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <li class="treeview" id="agent">
                <a href="#">
                    <i class="fa fa-user-plus"></i>
                    <span>代理管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="agent_index"><a href="{{route('admin.agent.index')}}"><i class="fa fa-circle-o"></i> 代理列表</a></li>
                    <li id="agent_user_info"><a href="{{route('admin.agent_user_info.index')}}"><i class="fa fa-circle-o"></i> 代理信息审核</a></li>
                </ul>
            </li>
            <li class="treeview" id="merchant">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>商户管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="merchant_index"><a href="{{route('admin.merchant.index')}}"><i class="fa fa-circle-o"></i> 商户列表</a></li>
                    <li id="merchant_user_info"><a href="{{route('admin.merchant_user_info.index')}}"><i class="fa fa-circle-o"></i> 商户信息审核</a></li>
                </ul>
            </li>
            <li class="treeview" id="transaction">
                <a href="#">
                    <i class="fa fa-bar-chart"></i>
                    <span>交易管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="order"><a href="{{route('admin.order.index')}}"><i class="fa fa-circle-o"></i> 订单列表</a></li>
                    <li id="operation_report"><a href="{{route('admin.operation_report.index')}}"><i class="fa fa-circle-o"></i> 经营报表</a></li>
                </ul>
            </li>
            <li class="treeview" id="account">
                <a href="#">
                    <i class="fa fa-table"></i>
                    <span>账变管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="agent_withdrawal"><a href="{{route('admin.agent_withdrawal.index')}}"><i class="fa fa-circle-o"></i> 代理商提现列表</a></li>
                    <li id="agent_account"><a href="{{route('admin.agent_account.index')}}"><i class="fa fa-circle-o"></i> 代理商账变列表</a></li>
                    <li id="platform_account"><a href="{{route('admin.platform_account.index')}}"><i class="fa fa-circle-o"></i> 平台账变列表</a></li>
                </ul>
            </li>
            <li class="treeview" id="finance">
                <a href="#">
                    <i class="fa fa-table"></i>
                    <span>财务管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="finance_merchant_settle_list"><a href="{{route('admin.merchant_settle.index')}}"><i class="fa fa-circle-o"></i> 商户对账单列表</a></li>
                </ul>
            </li>
            <li class="treeview" id="tube">
                <a href="#">
                    <i class="fa fa-link"></i>
                    <span>通道管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>

                </a>
                <ul class="treeview-menu">
                    <li id="tube_channel_list"><a href="{{route('admin.channel.index')}}"><i class="fa fa-circle-o"></i> 渠道列表</a></li>
                    <li id="tube_tube_list"><a href="{{route('admin.tube.index')}}"><i class="fa fa-circle-o"></i> 通道列表</a></li>
                </ul>
            </li>
            <li class="treeview" id="system">
                <a href="#">
                    <i class="fa fa-cog"></i>
                    <span>系统设置</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="system_admin_index"><a href="{{route('admin.admin.index')}}"><i class="fa fa-circle-o"></i> 管理员管理</a></li>
                    <li id="system_role_index"><a href="{{route('admin.role.index')}}"><i class="fa fa-circle-o"></i> 角色管理</a></li>
                    <li id="action_log"><a href="{{route('admin.action_log.index')}}"><i class="fa fa-circle-o"></i> 操作日志</a></li>
                </ul>
            </li>
        </ul>--}}
    </section>
</aside>
