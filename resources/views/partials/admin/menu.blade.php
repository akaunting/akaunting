@stack('menu_start')
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-default" id="sidenav-main">
        <div class="scrollbar-inner">
            <div class="sidenav-header d-flex align-items-center">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="avatar menu-avatar background-unset">
                                <img class="border-radius-none border-0 mr-3" alt="Akaunting" src="{{ asset('public/img/akaunting-logo-white.svg') }}">
                            </span>
                            <span class="nav-link-text long-texts pl-2 mwpx-100">{{ Str::limit(setting('company.name'), 22) }}</span>
                            @permission('read-common-companies')
                                <i class="fas fa-sort-down pl-2"></i>
                            @endpermission
                        </a>
                        @permission('read-common-companies')
                            <div class="dropdown-menu dropdown-menu-right menu-dropdown menu-dropdown-width">
                                @foreach($companies as $com)
                                    <a href="{{ route('companies.switch', $com->id) }}" class="dropdown-item">
                                        <i class="fas fa-building"></i>
                                        <span>{{ Str::limit($com->name, 18) }}</span>
                                    </a>
                                @endforeach
                                @permission('update-common-companies')
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('companies.index') }}" class="dropdown-item">
                                        <i class="fas fa-cogs"></i>
                                        <span>{{ trans('general.title.manage', ['type' => trans_choice('general.companies', 2)]) }}</span>
                                    </a>
                                @endpermission
                            </div>
                        @endpermission
                    </li>
                </ul>
                <div class="ml-auto left-menu-toggle-position overflow-hidden">
                    <div class="sidenav-toggler d-none d-xl-block left-menu-toggle" data-action="sidenav-unpin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            {!! menu('admin') !!}
        </div>
    </nav>
@stack('menu_end')
