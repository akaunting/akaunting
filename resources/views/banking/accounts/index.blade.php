@extends('layouts.admin')

@section('title', trans_choice('general.accounts', 2))

@section('new_button')
    @permission('create-banking-accounts')
        <a href="{{ route('accounts.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endpermission
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'accounts.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <akaunting-search
                        :placeholder="'{{ trans('general.search_placeholder') }}'"
                        :options="{{ json_encode([]) }}"
                    ></akaunting-search>
                </div>

                {{ Form::bulkActionRowGroup('general.accounts', $bulk_actions, ['group' => 'banking', 'type' => 'accounts']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-3 col-xl-2">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-md-2 col-lg-2 col-xl-3 d-none d-md-block text-left">@sortablelink('number', trans('accounts.number'))</th>
                        <th class="col-sm-2 col-md-2 col-lg-3 col-xl-4 d-none d-sm-block text-right">@sortablelink('opening_balance', trans('accounts.current_balance'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xl-1">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($accounts as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">
                                {{ Form::bulkActionGroup($item->id, $item->name) }}
                            </td>
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-3 col-xl-2"><a class="col-aka" href="{{ route('accounts.edit', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-md-2 col-lg-2 col-xl-3 d-none d-md-block text-left">{{ $item->number }}</td>
                            <td class="col-sm-2 col-md-2 col-lg-3 col-xl-4 d-none d-sm-block text-right">@money($item->balance, $item->currency_code, true)</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-2 col-xl-1">
                                @if (user()->can('update-banking-accounts'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                    @else
                                        <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('accounts.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-banking-accounts')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'accounts.destroy') !!}
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
                @include('partials.admin.pagination', ['items' => $accounts])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/accounts.js?v=' . version('short')) }}"></script>
@endpush
