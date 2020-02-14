@extends('layouts.admin')

@section('title', trans_choice('general.dashboards', 2))

@permission('create-common-dashboards')
    @section('new_button')
        <a href="{{ route('dashboards.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endsection
@endpermission

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'dashboards.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <akaunting-search
                        :placeholder="'{{ trans('general.search_placeholder') }}'"
                        :options="{{ json_encode([]) }}"
                    ></akaunting-search>
                </div>

                {{ Form::bulkActionRowGroup('general.dashboards', $bulk_actions, ['group' => 'common', 'type' => 'dashboards']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-3 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-6 col-lg-7 col-xl-7 long-texts">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($dashboards as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-3 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">
                                {{ Form::bulkActionGroup($item->id, $item->name) }}
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-6 col-lg-7 col-xl-7 long-texts"><a href="{{ route('dashboards.edit', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">
                                @if (user()->can('update-common-dashboards'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                    @else
                                        <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @if ($item->enabled)
                                            <a  class="dropdown-item" href="{{ route('dashboards.switch', $item->id) }}">{{ trans('general.switch') }}</a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('dashboards.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-common-dashboards')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'dashboards.destroy') !!}
                                        @endpermission
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $dashboards])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/dashboards.js?v=' . version('short')) }}"></script>
@endpush
