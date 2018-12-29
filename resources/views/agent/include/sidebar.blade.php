<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li id="index">
                <a href="{{route('agent.dashboard.index')}}">
                    <i class="fa fa-dashboard"></i> <span>数据中心</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>

            <li class="treeview" id="member">
                <a href="#">
                    <i class="fa  fa-user-plus"></i>
                    <span>成员管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="member_merchant"><a href="{{route('agent.merchant.index')}}"><i class="fa fa-circle-o"></i> 子商户列表</a></li>
                        <li id="member_agent"><a href="{{route('agent.agent.index')}}"><i class="fa fa-circle-o"></i> 子代理列表</a></li>
                </ul>
            </li>


            <li class="treeview" id="trade">
                <a href="#">
                    <i class="fa  fa-bar-chart"></i>
                    <span>交易管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="order_index"><a href="{{route('agent.order.index')}}"><i class="fa fa-circle-o"></i> 订单列表</a></li>
                    <li id="settle_log_index"><a href="{{route('agent.settle_log.index')}}"><i class="fa fa-circle-o"></i> 结算单列表</a></li>
                </ul>
            </li>
            <li class="treeview" id="account_log">
                <a href="#">
                    <i class="fa fa-table"></i>
                    <span>账户管理</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="account_log_index"><a href="{{route('agent.account_log.index')}}"><i class="fa fa-circle-o"></i> 账变列表</a></li>
                    <li id="withdrawal_index"><a href="{{route('agent.withdrawal.index')}}"><i class="fa fa-circle-o"></i> 提现列表</a></li>
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
                    <li id="system_profile"><a href="{{route('agent.profile.index')}}"><i class="fa fa-circle-o"></i> 账户信息</a></li>
                    <li id="system_change_password"><a href="{{route('agent.password.change_password')}}"><i class="fa fa-circle-o"></i> 修改密码</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
