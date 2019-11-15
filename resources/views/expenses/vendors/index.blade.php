@extends('layouts.admin')

@section('title', trans_choice('general.vendors', 2))

@section('new_button')
    @permission('create-expenses-vendors')
        <span><a href="{{ route('vendors.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ url('common/import/expenses/vendors') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('vendors.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'url' => 'expenses/vendors',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
                <div class="row" v-if="!bulk_action.show">
                    <div class="col-12 card-header-search">
                        <span class="table-text hidden-lg">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                    </div>
                </div>

                {{ Form::bulkActionRowGroup('general.vendors', $bulk_actions, 'expenses/vendors') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-md-2 col-lg-2 col-xl-2 hidden-md">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-sm-3 col-md-2 col-lg-2 col-xl-2 hidden-sm">@sortablelink('phone', trans('general.phone'))</th>
                        <th class="col-lg-2 col-xl-2 text-right hidden-lg">@sortablelink('unpaid', trans('general.unpaid'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($vendors as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">
                                {{ Form::bulkActionGroup($item->id, $item->name) }}
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                <a class="col-aka text-success " href="{{ route('vendors.show', $item->id) }}">{{ $item->name }}</a>
                            </td>
                            <td class="col-md-2 col-lg-2 col-xl-2 hidden-md o-y">
                                {{ !empty($item->email) ? $item->email : trans('general.na') }}
                            </td>
                            <td class="col-sm-3 col-md-2 col-lg-2 col-xl-2 hidden-sm o-y">
                                {{ $item->phone }}
                            </td>
                            <td class="col-lg-2 col-xl-2 text-right hidden-lg o-y">
                                @money($item->unpaid, setting('default.currency'), true)
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1">
                                @if (user()->can('update-expenses-vendors'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                    @else
                                        <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('vendors.show', $item->id) }}">{{ trans('general.show') }}</a>
                                        <a class="dropdown-item" href="{{ route('vendors.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('create-expenses-vendors')
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('vendors.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                        @endpermission
                                        @permission('delete-expenses-vendors')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'expenses/vendors') !!}
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
                @include('partials.admin.pagination', ['items' => $vendors, 'type' => 'vendors'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/expenses/vendors.js?v=' . version('short')) }}"></script>
@endpush
