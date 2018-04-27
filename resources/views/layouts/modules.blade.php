<html lang="{{ setting('general.default_locale') }}">
    @include('partials.modules.head')

    <body class="hold-transition {{ setting('general.admin_theme', 'skin-green-light') }} sidebar-mini fixed">
        <!-- Site wrapper -->
        <div class="wrapper">
            @include('partials.admin.header')

            @include('partials.admin.menu')

            @include('partials.admin.content')

            @include('partials.admin.footer')
        </div>
    </body>
</html>
