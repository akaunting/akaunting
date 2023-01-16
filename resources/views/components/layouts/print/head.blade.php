@props([
    'title',
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>

    <title>{!! $title !!} - @setting('company.name')</title>

    <base href="{{ config('app.url') . '/' }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/png">

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('public/css/print.css') }}" type="text/css">

    @if (isset($currency_style) && $currency_style || in_array(app()->getLocale(), ['zh-CN', 'ja-JP', 'zh-TW']))
    <style type="text/css">
        @font-face {
            font-family: 'Firefly Sung';
            font-weight: 'normal';
            src: url('{{ asset("/public/css/fonts/firefly_sung_normal.ttf") }}') format("truetype");
        }

        * {
            font-family: 'Firefly Sung', sans-serif !important;
        }
    </style>
    @else
    <style type="text/css">
        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>
    @endif

    @stack('css')

    @stack('stylesheet')

    @livewireStyles

    @stack('js')

    @stack('scripts')

    @stack('head_end')
</head>
