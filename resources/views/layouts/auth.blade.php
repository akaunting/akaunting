<html lang="{{ app()->getLocale() }}">

    @include('partials.auth.head')

    <body class="login-page">
        @stack('body_start')

        <div class="main-content mt-4">
            <div class="header">
                <div class="container">
                    <div class="header-body text-center">
                        <div class="row justify-content-center">
                            <div class="col-xl-5 col-lg-6 col-md-8">
                                <img class="mb-5" src="{{ asset('public/img/akaunting-logo-white.svg') }}" width="22%" alt="Akaunting"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @stack('login_box_start')
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-7">
                            <div class="card mb-0 login-card-bg">
                                <div class="card-body px-lg-5 py-lg-5">
                                    <div class="text-center text-white mb-4">
                                        <small>@yield('message')</small>
                                    </div>

                                    <div id="app">
                                        @stack('login_content_start')

                                        @yield('content')

                                        @stack('login_content_end')
                                        <notifications></notifications>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('login_box_end')

            @yield('forgotten-password')

            <footer>
                <div class="container mt-5 mb-4">
                    <div class="row align-items-center justify-content-xl-between">
                        <div class="col-xl-12">
                            <div class="copyright text-center text-white">
                                <small>
                                    {{ trans('footer.powered') }}: <a href="{{ trans('footer.link') }}" target="_blank" class="text-white">{{ trans('footer.software') }}</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

        @stack('body_end')

        @include('partials.auth.scripts')
    </body>

</html>
