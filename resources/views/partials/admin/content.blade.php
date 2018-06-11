<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-center">
        <h1>
            @yield('title')
            @yield('new_button')
            @if (!empty($suggestion_modules))
                @foreach($suggestion_modules as $s_module)
                    <span class="new-button">
                        <a href="{{ url($s_module->action_url) . '?' . http_build_query((array) $s_module->action_parameters) }}" class="btn btn-default btn-sm" target="{{ $s_module->action_target }}"><span class="fa fa-rocket"></span> &nbsp;{{ $s_module->name }}</a>
                    </span>
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
