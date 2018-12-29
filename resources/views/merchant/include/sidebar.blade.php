<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li id="index">
                <a href="{{route('merchant.dashboard.index')}}">
                    <i class="fa fa-dashboard"></i> <span>数据中心</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>



            <li class="treeview" id="member">
                <a href="#">
                    <i class="fa  fa-user-plus"></i>
                    <span>门店管理</span>
                    <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    <li id="member_store"><a href="{{route('merchant.store.index')}}"><i class="fa fa-circle-o"></i> 门店列表</a></li>
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
                    <li id="order_index"><a href="{{route('merchant.order.index')}}"><i class="fa fa-circle-o"></i> 订单列表</a></li>
                    @if(\App\User::getMerchantAuthUser()->isCheckedMerchant())
                        <li id="store_order_index"><a href="{{route('merchant.store_order.index')}}"><i class="fa fa-circle-o"></i> 门店订单列表</a></li>
                    @endif

                    <li id="settle_log_index"><a href="{{route('merchant.settle_log.index')}}"><i class="fa fa-circle-o"></i> 结算单列表</a></li>
                    @if(\App\User::getMerchantAuthUser()->isCheckedMerchant())
                        <li id="store_settle_log_index"><a href="{{route('merchant.store_settle_log.index')}}"><i class="fa fa-circle-o"></i> 门店结算单列表</a></li>
                    @endif
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
                    <li id="account_log_index"><a href="{{route('merchant.account_log.index')}}"><i class="fa fa-circle-o"></i> 账变列表</a></li>
                    <li id="withdrawal_index"><a href="{{route('merchant.withdrawal.index')}}"><i class="fa fa-circle-o"></i> 提现列表</a></li>
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
                    <li id="system_profile"><a href="{{route('merchant.profile.index')}}"><i class="fa fa-circle-o"></i> 账户信息</a></li>
                    <li id="system_change_password"><a href="{{route('merchant.password.change_password')}}"><i class="fa fa-circle-o"></i> 修改密码</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
