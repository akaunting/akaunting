<html lang="{{ app()->getLocale() }}" dir="{{language_direction()}}">

@include('partials.print.head')

    <body onload="window.print();">
        @stack('body_start')

            @yield('content')

        @stack('body_end')
        
        @include('partials.print.scripts')
    </body>
</html>
