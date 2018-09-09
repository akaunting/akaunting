@stack('content_start')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @stack('content_wrapper_start')

    <!-- Content Header (Page header) -->
    <section class="content-header content-center">
        @stack('content_header_start')

        <h1>
            @yield('title')
            @yield('new_button')
        </h1>

        @stack('content_header_end')
    </section>

    <!-- Main content -->
    <section class="content content-center">
        @include('flash::message')

        @stack('content_content_start')

        @yield('content')

        @stack('content_content_end')
    </section>
    <!-- /.content -->

    @stack('content_wrapper_end')
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>

@stack('content_end')
