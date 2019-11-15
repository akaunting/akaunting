@extends('layouts.admin')

@section('title', trans_choice('general.reconciliations', 2))

@section('new_button')
    @permission('create-banking-reconciliations')
        <span><a href="{{ route('reconciliations.create') }}" class="btn btn-success btn-sm btn-alone"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    @endpermission
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'url' => 'banking/reconciliations',
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

                {{ Form::bulkActionRowGroup('general.reconciliations', $bulk_actions, 'banking/reconciliations') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-sm-3 col-md-2 col-lg-2 hidden-sm">@sortablelink('created_at', trans('general.created_date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-3 col-md-3 col-lg-3">@sortablelink('account_id', trans_choice('general.accounts', 1))</th>
                        <th class="col-lg-2 hidden-lg">{{ trans('general.period') }}</th>
                        <th class="col-md-2 col-lg-2 hidden-md text-right">@sortablelink('closing_balance', trans('reconciliations.closing_balance'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($reconciliations as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 hidden-sm">{{ Form::bulkActionGroup($item->id, $item->account->name) }}</td>
                            <td class="col-sm-3 col-md-2 col-lg-2 hidden-sm"><a class="col-aka text-success" href="{{ route('reconciliations.edit', $item->id) }}">@date($item->created_at)</a></td>
                            <td class="col-xs-4 col-sm-3 col-md-3 col-lg-3">{{ $item->account->name }}</td>
                            <td class="col-lg-2 hidden-lg border-0">@date($item->started_at) - @date($item->ended_at)</td>
                            <td class="col-md-2 col-lg-2 hidden-md text-right">@money($item->closing_balance, $item->account->currency_code, true)</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1">
                                @if (user()->can('update-banking-reconciliations'))
                                    {{ Form::enabledGroup($item->id, $item->account->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                    @else
                                        <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('reconciliations.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-banking-reconciliations')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'banking/reconciliations') !!}
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
                @include('partials.admin.pagination', ['items' => $reconciliations, 'type' => 'reconciliations'])
            </div>
       </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/reconciliations.js?v=' . version('short')) }}"></script>
@endpush
