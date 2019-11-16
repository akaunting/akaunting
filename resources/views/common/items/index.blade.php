@extends('layouts.admin')

@section('title', trans_choice('general.items', 2))

@section('new_button')
    @permission('create-common-items')
        <span><a href="{{ route('items.create') }}" class="btn btn-sm btn-success header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ route('import.create', ['common', 'items']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload "></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('items.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'route' => 'items.index',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
                <div class="row" v-if="!bulk_action.show">
                    <div class="col-12 card-header-search card-header-space">
                        <span class="table-text hidden-lg card-header-search-text">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                    </div>
                </div>

                {{ Form::bulkActionRowGroup('general.items', $bulk_actions, 'common/items') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-4 col-lg-3 col-xl-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-lg-1 col-xl-2 hidden-lg">@sortablelink('category', trans_choice('general.categories', 1))</th>
                        <th class="col-md-3 col-lg-3 col-xl-2 text-right hidden-md">@sortablelink('sale_price', trans('items.sales_price'))</th>
                        <th class="col-lg-2 col-xl-2 text-right hidden-lg">@sortablelink('purchase_price', trans('items.purchase_price'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($items as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 hidden-sm">
                                {{ Form::bulkActionGroup($item->id, $item->name) }}
                            </td>
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-3 col-xl-3 py-2">
                                <img src="{{ $item->picture ? Storage::url($item->picture->id) : asset('public/img/akaunting-logo-green.png') }}" class="avatar image-style p-1 mr-3 item-img hidden-md col-aka" alt="{{ $item->name }}">
                                <a class="text-success" href="{{ route('items.edit', $item->id) }}">{{ $item->name }}</a>
                            </td>
                            <td class="col-lg-1 col-xl-2 hidden-lg">
                                {{ $item->category ? $item->category->name : trans('general.na') }}
                            </td>
                            <td class="col-md-3 col-lg-3 col-xl-2 text-right hidden-md">
                                {{ money($item->sale_price, setting('default.currency'), true) }}
                            </td>
                            <td class="col-lg-2 col-xl-2 text-right hidden-lg">
                                {{ money($item->purchase_price, setting('default.currency'), true) }}
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1">
                                @if (user()->can('update-common-items'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                    @else
                                        <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('items.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        <div class="dropdown-divider"></div>
                                        @permission('create-common-items')
                                            <a class="dropdown-item" href="{{ route('items.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                            <div class="dropdown-divider"></div>
                                        @endpermission
                                        @permission('delete-common-items')
                                            {!! Form::deleteLink($item, 'common/items') !!}
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
            <div class="row align-items-center">
                @include('partials.admin.pagination', ['items' => $items, 'type' => 'items'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/items.js?v=' . version('short')) }}"></script>
@endpush
