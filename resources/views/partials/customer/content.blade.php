<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-center">
        <h1>
            @yield('title')
            @yield('new_button')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content content-center">
        @include('flash::message')

        @yield('content')
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script type="text/javascript">
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
</script>
