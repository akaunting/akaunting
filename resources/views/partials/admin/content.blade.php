<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-center">
        <h1>
            @yield('title')
            @yield('new_button')
            @if ($suggestion_modules)
                @foreach($suggestion_modules as $suggestion_module)
                    <span class="new-button"><a href="{{ url($suggestion_module->action_url) }}" target="_blank" class="btn btn-default btn-sm"><span class="fa fa-rocket"></span> &nbsp;{{ $suggestion_module->name }}</a></span>
                @endforeach
            @endif
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
