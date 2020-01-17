<html lang="{{ app()->getLocale() }}">

    @include('partials.print.head')

    <body onload="window.print();">
        @stack('body_start')

            @yield('content')

        @stack('body_end')
    </body>

</html>
