@stack('header_start')
    <div id="header" class="header pb-6">
        <div class="container-fluid content-layout">
            <div class="header-body">
                <div class="row py-4 align-items-center">
                    <div class="col-xs-12 col-sm-4 col-md-5 align-items-center">
                        <h2 class="d-inline-flex mb-0 long-texts">@yield('title')</h2>
                        @yield('dashboard_action')
                    </div>

                    <div class="col-xs-12 col-sm-8 col-md-7">
                        <div class="text-right">
                            @yield('new_button')

                            @permission('read-modules-home')
                                @if (!empty($suggestion_modules))
                                    @foreach($suggestion_modules as $s_module)
                                        <span>
                                            <a href="{{ url($s_module->action_url) . '?' . http_build_query((array) $s_module->action_parameters) }}" class="btn btn-white btn-sm header-button-bottom" target="{{ $s_module->action_target }}"><span class="fa fa-rocket"></span> &nbsp;{{ $s_module->name }}</a>
                                        </span>
                                    @endforeach
                                @endif
                            @endpermission

                            @stack('header_button')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stack('header_end')
