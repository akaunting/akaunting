<html lang="{{ setting('general.default_locale') }}">
    @include('partials.invoice.head')

    <body onload="window.print();">
        @stack('body_start')

        @yield('content')

        @stack('body_end')
    </body>
</html>
