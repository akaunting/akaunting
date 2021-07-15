@stack('navbar_start')
<nav class="navbar navbar-top navbar-expand navbar-dark border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            @stack('navbar_search')

            @can('read-common-search')
                <livewire:common.search />
            @endcan

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

                @stack('navbar_create')

                <li class="nav-item d-sm-none">
                    <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
                        <i class="fa fa-search"></i>
                    </a>
                </li>

                @canany(['create-sales-invoices', 'create-sales-revenues', 'create-sales-invoices', 'create-purchases-bills', 'create-purchases-payments', 'create-purchases-vendors'])
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark dropdown-menu-right">
                            <div class="row shortcuts px-4">
                                @stack('navbar_create_invoice')

                                @can('create-sales-invoices')
                                    <a href="{{ route('invoices.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                                        <i class="fa fa-money-bill"></i>
                                        </span>
                                        <small class="text-info">{{ trans_choice('general.invoices', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_revenue')

                                @can('create-sales-revenues')
                                    <a href="{{ route('revenues.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </span>
                                        <small class="text-info">{{ trans_choice('general.revenues', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_customer')

                                @can('create-sales-customers')
                                    <a href="{{ route('customers.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                                        <i class="fas fa-user"></i>
                                        </span>
                                        <small class="text-info">{{ trans_choice('general.customers', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_bill')

                                @can('create-purchases-bills')
                                    <a href="{{ route('bills.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-danger">
                                        <i class="fa fa-shopping-cart"></i>
                                        </span>
                                        <small class="text-danger">{{ trans_choice('general.bills', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_payment')

                                @can('create-purchases-payments')
                                    <a href="{{ route('payments.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-danger">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </span>
                                        <small class="text-danger">{{ trans_choice('general.payments', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_vendor_start')

                                @can('create-purchases-vendors')
                                    <a href="{{ route('vendors.create') }}" class="col-4 shortcut-item">
                                        <span class="shortcut-media avatar rounded-circle bg-gradient-danger">
                                        <i class="fas fa-user"></i>
                                        </span>
                                        <small class="text-danger">{{ trans_choice('general.vendors', 1) }}</small>
                                    </a>
                                @endcan

                                @stack('navbar_create_vendor_end')
                            </div>
                        </div>
                    </li>
                @endcanany

                @stack('navbar_notifications')

                @can('read-common-notifications')
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>
                                <i class="far fa-bell"></i>
                            </span>
                            @if ($notifications)
                                <span class="badge badge-md badge-circle badge-reminder badge-warning">{{ $notifications }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right py-0 overflow-hidden">
                            @if ($notifications)
                                <div class="p-3">
                                    <a class="text-sm text-muted">{{ trans_choice('header.notifications.counter', $notifications, ['count' => $notifications]) }}</a>
                                </div>
                            @endif

                            <div class="list-group list-group-flush">
                                @stack('notification_new_apps_start')

                                @if (!empty($new_apps) && count($new_apps))
                                    <a href="{{ route('notifications.index') . '#new-apps' }}" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="fa fa-rocket"></i>
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.new_apps', count($new_apps), ['count' => count($new_apps)]) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @stack('notification_new_apps_end')

                                @stack('notification_exports_completed_start')

                                @if (!empty($exports['completed']) && count($exports['completed']))
                                    <a href="{{ route('notifications.index') . '#exports' }}" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="fas fa-file-export"></i>
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.exports.completed', count($exports['completed']), ['count' => count($exports['completed'])]) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @stack('notification_exports_completed_end')

                                @stack('notification_exports_failed_start')

                                @if (!empty($exports['failed']) && count($exports['failed']))
                                    <a href="{{ route('notifications.index') . '#exports' }}" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="fas fa-file-export"></i>
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.exports.failed', count($exports['failed']), ['count' => count($exports['failed'])]) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @stack('notification_exports_failed_end')

                                @stack('notification_imports_completed_start')

                                @if (!empty($imports['completed']) && count($imports['completed']))
                                    <a href="{{ route('notifications.index') . '#imports' }}" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="fas fa-file-import"></i>
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.imports.completed', count($imports['completed']), ['count' => count($imports['completed'])]) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @stack('notification_imports_completed_end')

                                @stack('notification_imports_failed_start')

                                @if (!empty($imports['failed']) && count($imports['failed']))
                                    <a href="{{ route('notifications.index') . '#imports' }}" class="list-group-item list-group-item-action">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="fas fa-file-import"></i>
                                            </div>
                                            <div class="col ml--2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h4 class="mb-0 text-sm">{{ trans_choice('header.notifications.imports.failed', count($imports['failed']), ['count' => count($imports['failed'])]) }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif

                                @stack('notification_imports_failed_end')

                                @stack('notification_bills_start')

                                @can('read-purchases-bills')
                                    @if (count($bills))
                                        <a href="{{ route('notifications.index') . '#reminder-bill' }}" class="list-group-item list-group-item-action">
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
                                @endcan

                                @stack('notification_bills_end')

                                @stack('notification_invoices_start')

                                @can('read-sales-invoices')
                                    @if (count($invoices))
                                        <a href="{{ route('notifications.index') . '#reminder-invoice' }}" class="list-group-item list-group-item-action">
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
                                @endcan

                                @stack('notification_invoices_end')
                            </div>

                            @if ($notifications)
                                <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary font-weight-bold py-3">{{ trans('header.notifications.view_all') }}</a>
                            @else
                                <a class="dropdown-item text-center text-primary font-weight-bold py-3">{{ trans_choice('header.notifications.counter', $notifications, ['count' => $notifications]) }}</a>
                            @endif
                        </div>
                    </li>
                @endcan

                @stack('navbar_updates')

                @can('read-install-updates')
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
                @endcan

                @stack('navbar_help_start')

                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" href="{{ url(trans('header.support_link')) }}" target="_blank" title="{{ trans('general.help') }}" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-life-ring"></i>
                    </a>
                </li>

                @stack('navbar_help_end')
            </ul>

            @stack('navbar_profile')

            <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <div class="media align-items-center">
                            @if (setting('default.use_gravatar', '0') == '1')
                                <img src="{{ $user->picture }}" alt="{{ $user->name }}" class="rounded-circle image-style user-img" title="{{ $user->name }}">
                            @elseif (is_object($user->picture))
                                <img src="{{ Storage::url($user->picture->id) }}" class="rounded-circle image-style user-img" alt="{{ $user->name }}" title="{{ $user->name }}">
                            @else
                                <img src="{{ asset('public/img/user.svg') }}" class="user-img" alt="{{ $user->name }}"/>
                            @endif
                            @if (!empty($user->name))
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm font-weight-bold">{{ $user->name }}</span>
                                </div>
                            @endif
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        @stack('navbar_profile_welcome')

                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">{{ trans('general.welcome') }}</h6>
                        </div>

                        @stack('navbar_profile_edit')

                        @canany(['read-auth-users', 'read-auth-profile'])
                            <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>{{ trans('auth.profile') }}</span>
                            </a>
                        @endcanany
                        
                        @stack('navbar_profile_edit_end')

                        @canany(['read-auth-users', 'read-auth-roles', 'read-auth-permissions'])
                            <div class="dropdown-divider"></div>

                            @stack('navbar_profile_users')

                            @can('read-auth-users')
                                <a href="{{ route('users.index') }}" class="dropdown-item">
                                    <i class="fas fa-users"></i>
                                    <span>{{ trans_choice('general.users', 2) }}</span>
                                </a>
                            @endcan

                            @stack('navbar_profile_roles')

                            @can('read-auth-roles')
                                <a href="{{ route('roles.index') }}" class="dropdown-item">
                                    <i class="fas fa-list"></i>
                                    <span>{{ trans_choice('general.roles', 2) }}</span>
                                </a>
                            @endcan

                            @stack('navbar_profile_permissions_start')

                            @can('read-auth-permissions')
                                <a href="{{ route('permissions.index') }}" class="dropdown-item">
                                    <i class="fas fa-key"></i>
                                    <span>{{ trans_choice('general.permissions', 2) }}</span>
                                </a>
                            @endcan

                            @stack('navbar_profile_permissions_end')
                        @endcanany

                        <div class="dropdown-divider"></div>

                        @stack('navbar_profile_logout_start')

                        <a href="{{ route('logout') }}" class="dropdown-item">
                            <i class="fas fa-power-off"></i>
                            <span>{{ trans('auth.logout') }}</span>
                        </a>

                        @stack('navbar_profile_logout_end')
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
@stack('navbar_end')
