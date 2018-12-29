<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <span class="logo-mini"><img src="/img/logo.png" alt="" width="30"></span>
        <span class="logo-lg"><b>门店</b>后台</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="hidden-xs">{{ Auth('stores')->user()->mobile }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        {{--<li class="user-header">
                            <img src="{{asset('img/admin/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                            <p>
                                {{ Auth()->user()->mobile }}
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>--}}
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ route('stores.logout') }}" class="btn btn-default btn-flat">注销</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>