<nav class="navbar navbar-top navbar-expand navbar-dark border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav align-items-center ml-md-auto">
                <li class="nav-item d-xl-none">
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-bell"></i>
                        @if ($notifications)
                            <span class="badge badge-md badge-circle badge-reminder badge-warning">{{ $notifications }}</span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                        @if ($notifications)
                            <div class="p-3">
                                <a class="text-sm text-muted m-0">{{ trans_choice('header.notifications.counter', $notifications, ['count' => $notifications]) }}</a>
                            </div>
                        @endif

                        <div class="list-group list-group-flush">
                            @if (count($bills))
                                <a href="{{ route('users.read.bills', $user->id) }}" class="list-group-item list-group-item-action">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="fa fa-shopping-cart"></i>
                                        </div>
                                        <div class="col ml--2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.upcoming_bills', count($bills), ['count' => count($bills)]) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            @if (count($invoices))
                                <a href="{{ route('users.read.invoices', $user->id) }}" class="list-group-item list-group-item-action">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="fa fa-money-bill"></i>
                                        </div>
                                        <div class="col ml--2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.overdue_invoices', count($invoices), ['count' => count($invoices)]) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        </div>

                        @if ($notifications)
                            <a href="#" class="dropdown-item text-center text-primary font-weight-bold py-3">{{ trans('header.notifications.view_all') }}</a>
                        @else
                            <a class="dropdown-item text-center text-primary font-weight-bold py-3">{{ trans_choice('header.notifications.counter', $notifications, ['count' => $notifications]) }}</a>
                        @endif
                    </div>
                </li>

                @permission('read-install-updates')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('updates.index') }}" title="{{ $updates }} Updates Available" role="button" aria-haspopup="true" aria-expanded="false">
                            <span>
                                <i class="fa fa-sync-alt"></i>
                            </span>
                            @if ($updates)
                                <span class="badge badge-md badge-circle badge-update badge-warning">{{ $updates }}</span>
                            @endif
                        </a>
                    </li>
                @endpermission

                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="{{ url(trans('header.support_link')) }}" target="_blank" title="{{ trans('general.help') }}" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-life-ring"></i>
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                            <img src="{{ asset('public/img/user.svg') }}" height="36" width="36" alt="User"/>
                            <div class="media-body ml-2">
                                <span class="mb-0 text-sm font-weight-bold">
                                    @if (!empty($user->name))
                                        {{ $user->name }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ trans('general.welcome') }}</h6>
                        </div>
                        <a href="{{ route('portal.profile.edit', $user->id) }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>{{ trans('auth.profile') }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('portal.logout') }}" class="dropdown-item">
                            <i class="fas fa-power-off"></i>
                            <span>{{ trans('auth.logout') }}</span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
