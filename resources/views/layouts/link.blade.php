<html lang="{{ app()->getLocale() }}">
    @include('partials.link.head')

    <body class="hold-transition skin-green-light sidebar-mini fixed link">
        @stack('body_start')

        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.link.content')

            @include('partials.link.footer')
        </div>

        @stack('body_end')
    </body>
</html>
