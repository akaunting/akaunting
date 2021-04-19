<head>
    @stack('head_start')

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8; charset=ISO-8859-1"/>
    <meta name="robots" content="noindex,nofollow">

    <title>{{ trans('maintenance.title') }}</title>

    <base href="{{ config('app.url') . '/' }}">

    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans" type="text/css">
    <link rel="stylesheet" href="{{ asset('public/css/maintenance.css?v=' . version('short')) }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    <script type="text/javascript"><!--
        var url = '{{ url("/" . company_id()) }}';
        var app_url = '{{ config("app.url") }}';
    //--></script>

    @stack('js')

    @stack('head_end')
</head>
