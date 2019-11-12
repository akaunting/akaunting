@stack('menu_start')

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ setting('general.company_logo') ? Storage::url(setting('general.company_logo')) : asset('public/img/company.png') }}" class="img-circle" alt="@setting('general.company_name')">
            </div>
            <div class="pull-left info">
                <p>{{ str_limit(setting('general.company_name'), 22) }}</p>
                @permission('read-common-companies')
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="caret"></span> &nbsp;{{ trans('general.switch') }}</a>
                <ul class="dropdown-menu">
                    @foreach($companies as $com)
                    <li><a href="{{ url('common/companies/'. $com->id .'/set') }}">{{ str_limit($com->company_name, 18) }}</a></li>
                    @endforeach
                    @permission('update-common-companies')
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ url('common/companies') }}">{{ trans('companies.manage') }}</a></li>
                    @endpermission
                </ul>
                @endpermission
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" id="form-search" class="sidebar-form">
            <div id="live-search" class="input-group">
                <input type="text" name="live-search" value="<?php //echo $search; ?>" id="input-search" class="form-control" placeholder="{{ trans('general.search_placeholder') }}">
                <span class="input-group-btn">
                    <button type="submit" name="live-search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        {!! Menu::get('AdminMenu') !!}
    </section>
    <!-- /.sidebar -->
</aside>

@stack('menu_end')