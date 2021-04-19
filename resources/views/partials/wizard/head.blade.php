<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>@yield('title') - @setting('company.name')</title>

    <base href="{{ config('app.url') . '/' }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/png">

    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('public/vendor/opensans/css/opensans.css?v=' . version('short')) }}" type="text/css">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('public/vendor/nucleo/css/nucleo.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/vendor/fontawesome/css/all.min.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('public/css/argon.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/akaunting-color.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/custom.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/element.css?v=' . version('short')) }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    @livewireStyles

    <script type="text/javascript"><!--
        var url = '{{ url("/" . company_id()) }}';
        var app_url = '{{ config("app.url") }}';
        var aka_currency = {!! !empty($currency) ? $currency : 'false' !!};
    //--></script>

    @stack('js')

    <script type="text/javascript"><!--
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var flash_notification = {!! (session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false' !!};
    //--></script>

    {{ session()->forget('flash_notification') }}

    @stack('scripts')

    @stack('head_end')
</head>
