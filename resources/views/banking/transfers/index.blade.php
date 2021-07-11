@extends('layouts.admin')

@section('title', trans_choice('general.transfers', 2))

@section('new_button')
    @can('create-banking-transfers')
        <a href="{{ route('transfers.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endcan
    <a href="{{ route('import.create', ['banking', 'transfers']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    <a href="{{ route('transfers.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    @if ($transfers->count() || request()->get('search', false))
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'transfers.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <x-search-string model="App\Models\Banking\Transfer" />
                    </div>

                    {{ Form::bulkActionRowGroup('general.transfers', $bulk_actions, ['group' => 'banking', 'type' => 'transfers']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-md-2 d-none d-md-block">@sortablelink('expense_transaction.paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-sm-2 col-md-3 d-none d-sm-block">@sortablelink('expense_transaction.name', trans('transfers.from_account'))</th>
                            <th class="col-xs-4 col-sm-4 col-md-2">@sortablelink('income_transaction.name', trans('transfers.to_account'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 text-right">@sortablelink('expense_transaction.amount', trans('general.amount'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transfers as $item)
                            @php
                            $item->name = trans('transfers.messages.delete', [
                                'from' => $item->expense_transaction->account->name,
                                'to' => $item->income_transaction->account->name,
                                'amount' => money($item->expense_transaction->amount, $item->expense_transaction->currency_code, true)
                            ]);
                            @endphp
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->expense_transaction->account->name) }}</td>
                                <td class="col-md-2 d-none d-md-block"><a class="col-aka" href="{{ route('transfers.show', $item->id) }}">@date($item->expense_transaction->paid_at)</a></td>
                                <td class="col-sm-2 col-md-3 d-none d-sm-block long-texts">{{ $item->expense_transaction->account->name }}</td>
                                <td class="col-xs-4 col-sm-4 col-md-2 long-texts">{{ $item->income_transaction->account->name }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-right long-texts">@money($item->expense_transaction->amount, $item->expense_transaction->currency_code, true)</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('transfers.show', $item->id) }}">{{ trans('general.show') }}</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('transfers.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @can('delete-banking-transfers')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'transfers.destroy') !!}
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
                    @include('partials.admin.pagination', ['items' => $transfers])
                </div>
            </div>
        </div>
    @else
        <x-empty-page group="banking" page="transfers" />
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transfers.js?v=' . version('short')) }}"></script>
@endpush
