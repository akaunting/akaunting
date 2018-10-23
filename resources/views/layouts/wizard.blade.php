<html lang="{{ setting('general.default_locale') }}">
    @include('partials.wizard.head')

    <body class="hold-transition {{ setting('general.admin_theme', 'skin-green-light') }} sidebar-mini fixed">
        @stack('body_start')

        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.wizard.header')

            @include('partials.wizard.content')

            @include('partials.wizard.footer')
        </div>

        @stack('body_end')
    </body>
</html>
