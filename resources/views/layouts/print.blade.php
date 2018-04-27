<html lang="{{ setting('general.default_locale') }}">
    @include('partials.admin.head')

    @push('css')
    <!-- Bootstrap 3 print fix -->
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap3-print-fix.css?v=1.2') }}">
    @endpush

    <body onload="window.print();" class="print-width">
        @yield('content')
    </body>
</html>
