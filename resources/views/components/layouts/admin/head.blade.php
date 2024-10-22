@props([
    'metaTitle', 'title', 'currency'
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>{!! ! empty($metaTitle) ? $metaTitle : $title !!} - @setting('company.name')</title>

    <base href="{{ config('app.url') . '/' }}">

    <x-layouts.pwa.head />

    <link rel="stylesheet" href="{{ asset('public/css/custom_loading.css?v=' . version('short')) }}" type="text/css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/png">

    <!--Icons-->
    <link rel="stylesheet" href="{{ asset('public/css/fonts/material-icons/style.css?v=' . version('short')) }}" type="text/css">

     <!-- Font -->
    <link rel="stylesheet" href="{{ asset('public/vendor/quicksand/css/quicksand.css?v=' . version('short')) }}" type="text/css">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('public/css//third_party/swiper-bundle.min.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css//third_party/vue-html-editor.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/element.css?v=' . version('short')) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/app.css?v=' . version('short')) }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    @livewireStyles

    <script type="text/javascript"><!--
        var url = '{{ url("/" . company_id()) }}';
        var app_url = '{{ config("app.url") }}';
        var aka_currency = {!! !empty($currency) ? json_encode($currency) : 'false' !!};
        var all_currencies = {!! !empty($currencies) ? json_encode($currencies) : '[]' !!};
    //--></script>

    <x-script.exceptions.trackers />

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
