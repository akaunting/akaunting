@extends('layouts.admin')

@section('title', trans_choice('general.items', 2))

@section('new_button')
    @permission('create-common-items')
        <span><a href="{{ route('items.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ route('import.create', ['common', 'items']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload "></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('items.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    @if ($items->count())
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'items.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <akaunting-search
                            :placeholder="'{{ trans('general.search_placeholder') }}'"
                            :options="{{ json_encode([]) }}"
                        ></akaunting-search>
                    </div>

                    {{ Form::bulkActionRowGroup('general.items', $bulk_actions, ['group' => 'common', 'type' => 'items']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-xs-4 col-sm-4 col-md-4 col-lg-3 col-xl-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-lg-1 col-xl-2 d-none d-lg-block">@sortablelink('category', trans_choice('general.categories', 1))</th>
                            <th class="col-md-3 col-lg-3 col-xl-2 text-right d-none d-md-block">@sortablelink('sale_price', trans('items.sales_price'))</th>
                            <th class="col-lg-2 col-xl-2 text-right d-none d-lg-block">@sortablelink('purchase_price', trans('items.purchase_price'))</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center">@sortablelink('enabled', trans('general.enabled'))</th>
                            <th class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">
                                    {{ Form::bulkActionGroup($item->id, $item->name) }}
                                </td>
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-3 col-xl-3 py-2">
                                    <img src="{{ $item->picture ? Storage::url($item->picture->id) : asset('public/img/akaunting-logo-green.svg') }}" class="avatar image-style p-1 mr-3 item-img hidden-md col-aka" alt="{{ $item->name }}">
                                    <a href="{{ route('items.edit', $item->id) }}">{{ $item->name }}</a>
                                </td>
                                <td class="col-lg-1 col-xl-2 d-none d-lg-block">
                                    {{ $item->category->name }}
                                </td>
                                <td class="col-md-3 col-lg-3 col-xl-2 text-right d-none d-md-block">
                                    {{ money($item->sale_price, setting('default.currency'), true) }}
                                </td>
                                <td class="col-lg-2 col-xl-2 text-right d-none d-lg-block">
                                    {{ money($item->purchase_price, setting('default.currency'), true) }}
                                </td>
                                <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 col-xl-1 text-center">
                                    @if (user()->can('update-common-items'))
                                        {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                    @else
                                        @if ($item->enabled)
                                            <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                        @else
                                            <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
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
                                            @permission('create-common-items')
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('items.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                            @endpermission
                                            @permission('delete-common-items')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'items.destroy') !!}
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
                    @include('partials.admin.pagination', ['items' => $items])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'items', 'docs_path' => 'items'])
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/items.js?v=' . version('short')) }}"></script>
@endpush
