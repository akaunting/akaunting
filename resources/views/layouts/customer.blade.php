<html lang="{{ app()->getLocale() }}">
    @include('partials.customer.head')

    <body class="hold-transition skin-green-light sidebar-mini fixed">
        @stack('body_start')

        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.customer.header')

            @include('partials.customer.menu')

            @include('partials.customer.content')

            @include('partials.customer.footer')
        </div>

        @stack('body_end')
    </body>
</html>
