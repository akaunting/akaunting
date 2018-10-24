<html lang="{{ setting('general.default_locale') }}">
    @include('partials.wizard.head')

    <body class="hold-transition {{ setting('general.admin_theme', 'skin-green-light') }} sidebar-mini fixed">
        @stack('body_start')

        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.wizard.content')
        </div>

        @stack('body_end')
    </body>
</html>
