@stack('header_start')

<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('customers') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="{{ !empty($company->company_logo) ? Storage::url($company->company_logo) : asset('public/img/company.png') }}" class="logo-image-mini" width="25"  alt="{{ $company->company_name }}"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{ !empty($company->company_logo) ? Storage::url($company->company_logo) : asset('public/img/company.png') }}" width="25"  alt="{{ $company->company_name }}"> <b>{{ str_limit($company->company_name, 15) }}</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        @stack('header_navbar_left')

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @stack('header_navbar_right')

                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        @if ($notifications)
                        <span class="label label-warning">{{ $notifications }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">{{ trans_choice('header.notifications.counter', $notifications, ['count' => $notifications]) }}</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                @if (count($invoices))
                                <li>
                                    <a href="{{ url('customers/profile/read-invoices') }}">
                                        <i class="fa fa-money text-red"></i> {{ trans_choice('header.notifications.overdue_invoices', count($invoices), ['count' => count($invoices)]) }}
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        <li class="footer"><a href="#">{{ trans('header.notifications.view_all') }}</a></li>
                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">{{ trans('header.change_language') }}</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            {!! language()->flags() !!}
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if ($user->picture)
                            <img src="{{ Storage::url($user->picture->id) }}" class="user-image" alt="User Image">
                        @else
                            <i class="fa fa-user-o"></i>
                        @endif
                        @if (!empty($user->name))
                            <span class="hidden-xs">{{ $user->name }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if ($user->picture)
                                <img src="{{ Storage::url($user->picture->id) }}" class="img-circle" alt="User Image">
                            @else
                                <i class="fa fa-4 fa-user-o" style="color: #fff; font-size: 7em;"></i>
                            @endif
                            <p>
                                @if (!empty($user->name))
                                {{ $user->name }}
                                @endif
                                <small>{{ trans('header.last_login', ['time' => $user->last_logged_in_at]) }}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            @permission('update-customers-profile')
                            <div class="pull-left">
                                <a href="{{ url('customers/profile/edit') }}" class="btn btn-default btn-flat">{{ trans('auth.profile') }}</a>
                            </div>
                            @endpermission
                            <div class="pull-right">
                                <a href="{{ url('customers/logout') }}" class="btn btn-default btn-flat">{{ trans('auth.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

@stack('header_end')
