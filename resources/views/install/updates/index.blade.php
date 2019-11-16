@extends('layouts.admin')

@section('title', trans_choice('general.updates', 2))

@section('new_button')
    <span><a href="{{ route('updates.check') }}" class="btn btn-warning btn-sm btn-alone"><span class="fa fa-history"></span> &nbsp;{{ trans('updates.check') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <span class="table-text text-primary">Akaunting</span>
        </div>

        <div class="card-body">
            <div class="row">
                @if (empty($core))
                    <div class="col-md-12">
                        {{ trans('updates.latest_core') }}
                    </div>
                @else
                    <div class="col-sm-2 col-md-6 o-y">
                        {{ trans('updates.new_core') }}
                    </div>
                    <div class="col-sm-10 col-md-6 text-right">
                        <a href="{{ url('install/updates/update', ['alias' => 'core', 'version' => $core]) }}" data-toggle="tooltip" title="{{ trans('updates.update', ['version' => $core]) }}" class="btn btn-info btn-sm header-button-top o-y"><i class="fa fa-refresh"></i> &nbsp;{{ trans('updates.update', ['version' => $core]) }}</a>
                        <a href="{{ route('updates.changelog') }}" data-toggle="tooltip" title="{{ trans('updates.changelog') }}" class="btn btn-white btn-sm header-button-bottom"><i class="fa fa-exchange-alt"></i> &nbsp;{{ trans('updates.changelog') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom-0">
            <span class="table-text">{{ trans_choice('general.modules', 2) }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover" id="tbl-translations">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-sm-4 col-md-4">{{ trans('general.name') }}</th>
                        <th class="col-md-2 hidden-md">{{ trans_choice('general.categories', 1) }}</th>
                        <th class="col-sm-3 col-md-2 hidden-sm">{{ trans('updates.installed_version') }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">{{ trans('updates.latest_version') }}</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($modules)
                        @foreach($modules as $module)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-xs-4 col-sm-4 col-md-4">{{ $module->name }}</td>
                                <td class="col-md-2 hidden-md">{{ $module->category }}</td>
                                <td class="col-sm-3 col-md-2 hidden-sm">{{ $module->installed }}</td>
                                <td class="col-xs-4 col-md-2 col-sm-3">{{ $module->latest }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-center">
                                    <a href="{{ url('install/updates/update/' . $module->alias . '/' . $module->latest) }}" class="btn btn-warning btn-sm"><i class="fa fa-refresh" aria-hidden="true"></i> {{ trans_choice('general.updates', 1) }}</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="row">
                            <td class="col-12">
                                <div class="text-sm text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                    <small>{{ trans('general.no_records') }}</small>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
