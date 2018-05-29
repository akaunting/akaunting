<html lang="{{ env('APP_LOCALE') }}">
    @include('partials.auth.head')

    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{ asset('public/img/akaunting-logo-white.png') }}" alt="Akaunting" />
            </div>
            <!-- /.login-logo -->

            <div class="login-box-body">
                <p class="login-box-msg">@yield('message')</p>

                @include('flash::message')

                @yield('content')
            </div>
            <!-- /.login-box-body -->

            <div class="login-box-footer">
                {{ trans('footer.powered') }}: <a href="https://akaunting.com" target="_blank">{{ trans('footer.software') }}</a>
            </div>
            <!-- /.login-box-footer -->
        </div>
    </body>
</html>
