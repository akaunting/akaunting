@stack('menu_start')

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {!! Menu::get('CustomerMenu') !!}
    </section>
    <!-- /.sidebar -->
</aside>

@stack('menu_end')