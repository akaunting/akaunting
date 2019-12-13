<html lang="{{ app()->getLocale() }}">

    @include('partials.admin.head')

    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>

    <body onload="window.print();">

        @stack('body_start')

            @yield('content')

        @stack('body_end')

    </body>

</html>
