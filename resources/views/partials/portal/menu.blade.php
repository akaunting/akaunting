@stack('menu_start')
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-default" id="sidenav-main">
        <div class="scrollbar-inner">
            <div class="sidenav-header d-flex align-items-center ml-4">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="avatar menu-avatar background-unset">
                                <img class="border-radius-none border-0 mr-3" alt="Image placeholder" src="{{ setting('company.logo') ? Storage::url(setting('company.logo')) : asset('public/img/akaunting-logo-white.svg') }}">
                            </span>
                            <span class="nav-link-text long-texts pl-2 mwpx-100">{{ Str::limit(setting('company.name'), 22) }}</span>
                        </a>
                    </li>
                </ul>
                <div class="ml-auto left-menu-toggle-position">
                    <div class="sidenav-toggler d-none d-xl-block left-menu-toggle" data-action="sidenav-unpin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            {!! menu('portal') !!}
        </div>
    </nav>
@stack('menu_end')
