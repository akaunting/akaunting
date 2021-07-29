<html lang="{{ app()->getLocale() }}">
    @include('partials.print.head')

    <body>
        @stack('body_start')

            @yield('content')

        @stack('body_end')
        
        @include('partials.print.scripts')
    </body>
</html>
