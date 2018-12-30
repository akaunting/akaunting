<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>@yield('title') - @setting('general.company_name')</title>

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins -->
    @if (setting('general.admin_theme', 'skin-green-light') == 'skin-green-light')
        <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/dist/css/skins/skin-green-light.min.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/dist/css/skins/skin-black.min.css') }}">
        <link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/skin-black.css?v=' . version('short')) }}">
    @endif
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/select2/select2.min.css') }}">
    <!-- App style -->
    <link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/app.css?v=' . version('short')) }}">
    <link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/akaunting-green.css?v=' . version('short')) }}">

    <link rel="shortcut icon" href="{{ asset(AKAUNTING_PUBLIC . 'img/favicon.ico') }}">

    @stack('css')

    @stack('stylesheet')
    <style type="text/css">
        .wizard-loading-bar {
            font-size: 35px;
            position: absolute;
            z-index: 999999;
            width: 100%;
            height: 100%;
            background: rgb(136, 136, 136);
            opacity: 0.2;
            -moz-border-radius-bottomleft: 1px;
            -moz-border-radius-bottomright: 1px;
            border-bottom-left-radius: 1px;
            border-bottom-right-radius: 1px;font-size: 35px;
            position: absolute;
            z-index: 999999;
            width: 100%;
            height: 100%;
            background: rgb(136, 136, 136);
            opacity: 0.2;
            -moz-border-radius-bottomleft: 1px;
            -moz-border-radius-bottomright: 1px;
            border-bottom-left-radius: 1px;
            border-bottom-right-radius: 1px;
        }

        .wizard-loading-spin {
            font-size: 100px;
            position: absolute;
            margin: auto;
            color: #fff;
            padding: 8% 40%;
            z-index: 9999;
        }
    </style>

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
    <!-- FastClick -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/fastclick/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/select2/select2.min.js') }}"></script>
    <!-- Mask Money -->
    <script src="{{ asset(AKAUNTING_PUBLIC . 'js/jquery/jquery.maskMoney.js') }}"></script>

    <script src="{{ asset(AKAUNTING_PUBLIC . 'js/app.js?v=' . version('short')) }}"></script>

    @stack('js')

    @stack('scripts')

    @stack('head_end')
</head>
