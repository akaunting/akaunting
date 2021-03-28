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
                            @stack('header_button_start')

                            @yield('new_button')

                            @stack('header_button_end')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stack('header_end')
