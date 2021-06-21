@extends('layouts.admin')

@section('title', trans_choice('general.reconciliations', 2))

@section('new_button')
    @can('create-banking-reconciliations')
        <a href="{{ route('reconciliations.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endcan
@endsection

@section('content')
    @if ($reconciliations->count() || request()->get('search', false))
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'reconciliations.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <x-search-string model="App\Models\Banking\Reconciliation" />
                    </div>

                    {{ Form::bulkActionRowGroup('general.reconciliations', $bulk_actions, ['group' => 'banking', 'type' => 'reconciliations']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-sm-3 col-md-2 col-lg-2 d-none d-sm-block">@sortablelink('created_at', trans('general.created_date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-3 col-sm-2 col-md-2 col-lg-2">@sortablelink('account_id', trans_choice('general.accounts', 1))</th>
                            <th class="col-md-2 col-lg-2 d-none d-lg-block">{{ trans('general.period') }}</th>
                            <th class="col-md-2 col-lg-2 d-none d-md-block text-right">@sortablelink('closing_balance', trans('reconciliations.closing_balance'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-2">@sortablelink('status', trans_choice('general.statuses', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-1 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($reconciliations as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 col-lg-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->account->name) }}</td>
                                <td class="col-sm-3 col-md-2 col-lg-2 d-none d-sm-block"><a class="col-aka" href="{{ route('reconciliations.edit', $item->id) }}">@date($item->created_at)</a></td>
                                <td class="col-xs-3 col-sm-2 col-md-2 col-lg-2 long-texts">{{ $item->account->name }}</td>
                                <td class="col-md-2 col-lg-2 d-none d-lg-block border-0">@date($item->started_at) - @date($item->ended_at)</td>
                                <td class="col-md-2 col-lg-2 d-none d-md-block text-right">@money($item->closing_balance, $item->account->currency_code, true)</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                                    @if ($item->reconciled)
                                        <span class="badge badge-pill badge-success">{{ trans('reconciliations.reconciled') }}</span>
                                    @else
                                        <span class="badge badge-pill badge-danger">{{ trans('reconciliations.unreconciled') }}</span>
                                    @endif
                                </td>
                                <td class="col-xs-4 col-sm-2 col-md-1 col-lg-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('reconciliations.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @can('delete-banking-reconciliations')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'reconciliations.destroy') !!}
                                            @endcan
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
                    @include('partials.admin.pagination', ['items' => $reconciliations])
                </div>
        </div>
        </div>
    @else
        <x-empty-page group="banking" page="reconciliations" />
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/reconciliations.js?v=' . version('short')) }}"></script>
@endpush
