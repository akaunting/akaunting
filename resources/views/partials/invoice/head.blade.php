<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>@yield('title') - @setting('general.company_name')</title>

    <link rel="stylesheet" href="{{ asset('public/css/invoice.css?v=' . version('short')) }}">

    <style type="text/css">
        * {
            font-family: DejaVu Sans;
        }
    </style>

    @stack('css')

    @stack('stylesheet')

    @stack('js')

    @stack('scripts')

    @stack('head_end')
</head>
