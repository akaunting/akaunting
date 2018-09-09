<html lang="{{ setting('general.default_locale') }}">
    @include('partials.bill.head')

    <body onload="window.print();">
        @stack('body_start')

        <!-- Content Wrapper. Contains page content -->
        <div class="wrapper" style="margin-left: 0; page-break-after: always;">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        @stack('body_end')
    </body>
</html>
