@extends('layouts.admin')

@section('title', trans_choice('general.updates', 2))

@section('new_button')
<span class="new-button"><a href="{{ url('install/updates/check') }}" class="btn btn-warning btn-sm"><span class="fa fa-history"></span> &nbsp;{{ trans('updates.check') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        <i class="fa fa-gear"></i>
        <h3 class="box-title">Akaunting</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        @if (empty($core))
        {{ trans('updates.latest_core') }}
        @else
            {{ trans('updates.new_core') }}
            <a href="{{ url('install/updates/update', ['alias' => 'core', 'version' => $core]) }}" data-toggle="tooltip" title="{{ trans('updates.update', ['version' => $core]) }}" class="btn btn-warning btn-xs"><i class="fa fa-refresh"></i> &nbsp;{{ trans('updates.update', ['version' => $core]) }}</a>
            <a href="{{ url('install/updates/changelog') }}" data-toggle="tooltip" title="{{ trans('updates.changelog') }}" class="btn btn-default btn-xs popup"><i class="fa fa-exchange"></i> &nbsp;{{ trans('updates.changelog') }}</a>
        @endif
    </div>
    <!-- /.box-body -->

</div>
<!-- /.box -->

<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        <i class="fa fa-rocket"></i>
        <h3 class="box-title">{{ trans_choice('general.modules', 2) }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-translations">
                <thead>
                    <tr>
                        <th class="col-md-4">{{ trans('general.name') }}</th>
                        <th class="col-md-2">{{ trans_choice('general.categories', 1) }}</th>
                        <th class="col-md-2">{{ trans('updates.installed_version') }}</th>
                        <th class="col-md-2">{{ trans('updates.latest_version') }}</th>
                        <th class="col-md-2">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($modules as $module)
                    <tr>
                        <td>{{ $module->name }}</td>
                        <td>{{ $module->category }}</td>
                        <td>{{ $module->installed }}</td>
                        <td>{{ $module->latest }}</td>
                        <td>
                            <a href="{{ url('install/updates/update/' . $module->alias . '/' . $module->latest) }}" class="btn btn-warning btn-xs"><i class="fa fa-refresh" aria-hidden="true"></i> {{ trans_choice('general.updates', 1) }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

</div>
<!-- /.box -->
@endsection
