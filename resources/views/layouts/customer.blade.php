<html lang="{{ setting('general.default_locale') }}">
    @include('partials.customer.head')

    <body class="hold-transition skin-green-light sidebar-mini fixed">
        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.customer.header')

            @include('partials.customer.menu')

            @include('partials.customer.content')

            @include('partials.customer.footer')
        </div>
    </body>
</html>
