<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title')</title>

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('public/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/dist/css/skins/skin-green-light.min.css') }}">
    <!-- App style -->
    <link rel="stylesheet" href="{{ asset('public/css/app.css?v=' . version('short')) }}">
    <link rel="stylesheet" href="{{ asset('public/css/akaunting-green.css?v=' . version('short')) }}">
    
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}">

    @stack('css')

    @stack('stylesheet')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>

    <script src="{{ asset('public/js/app.js?v=' . version('short')) }}"></script>

    @stack('js')

    @stack('scripts')

    @stack('head_end')
</head>
