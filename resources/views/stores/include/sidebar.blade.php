<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li id="index">
                <a href="{{route('stores.dashboard.index')}}">
                    <i class="fa fa-dashboard"></i> <span>数据中心</span>
                    <span class="pull-right-container"></span>
                </a>
            </li>
            <li class="treeview" id="order">
                <a href="#">
                    <i class="fa fa-table"></i>
                    <span>收款记录</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="order_list"><a href="{{route('stores.order.index')}}"><i class="fa fa-circle-o"></i> 记录列表</a></li>
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
                    <li id="system_profile"><a href="{{route('stores.profile.index')}}"><i class="fa fa-circle-o"></i> 账户信息</a></li>
                    <li id="system_change_password"><a href="{{route('stores.password.change_password')}}"><i class="fa fa-circle-o"></i> 修改密码</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>