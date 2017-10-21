<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            @if ($customer->picture)
            <div class="pull-left image">
                <img src="{{ Storage::url($customer->picture) }}" class="img-circle" alt="{{ $customer->name }}">
            </div>
            @endif
            <div class="pull-left info">
                <p>{{ $customer->name }}</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {!! Menu::get('CustomerMenu') !!}
    </section>
    <!-- /.sidebar -->
</aside>
