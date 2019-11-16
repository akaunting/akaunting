<head>
    @stack('head_start')
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

        <title>@yield('title') - @setting('company.name')</title>

        <!-- Css -->
        <!-- Argon -->
        <link rel="stylesheet" href="{{ asset('public/css/argon.css?v=1.1.0') }}" type="text/css">
        <!-- Color -->
        <link rel="stylesheet" href="{{ asset('public/css/akaunting-color.css?v=' . version('short')) }}">
        <!-- Custom -->
        <link rel="stylesheet" href="{{ asset('public/css/custom.css?v=' . version('short')) }}">

        @stack('css')

        @stack('stylesheet')

        @stack('js')

        @stack('scripts')
    @stack('head_end')
</head>
