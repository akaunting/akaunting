<html lang="{{ app()->getLocale() }}">
    @include('partials.admin.head')

    <body class="hold-transition {{ setting('general.admin_theme', 'skin-green-light') }} sidebar-mini fixed">
        @stack('body_start')

        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.admin.header')

            @include('partials.admin.menu')

            @include('partials.admin.content')

            @include('partials.admin.footer')
        </div>

        @stack('body_end')
    </body>
</html>
