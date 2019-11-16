@extends('layouts.portal')

@section('title', trans_choice('general.transactions', 2))

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'route' => 'portal.transactions.index',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}

                <div class="row">
                    <div class="col-12 card-header-search card-header-space">
                        <span class="table-text hidden-lg card-header-search-text">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                    </div>
                </div>

            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-md-2 hidden-md">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-xs-5 col-sm-3 col-md-2">@sortablelink('account.name', trans('accounts.account_name'))</th>
                        <th class="col-xs-3 col-sm-3 col-md-2">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-sm-3 col-md-2 hidden-sm">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-md-2 hidden-md">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">@sortablelink('amount', trans('general.amount'))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-md-2 hidden-md">@date($item->date)</td>
                            <td class="col-xs-5 col-sm-3 col-md-2">{{ $item->account->name }}</td>
                            <td class="col-xs-3 col-sm-3 col-md-2">{{ trans_choice('general.payments', 1) }}</td>
                            <td class="col-sm-3 col-md-2 hidden-sm">{{ $item->category->name }}</td>
                            <td class="col-md-2 hidden-md">{{ $item->description }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-2">@money($item->amount, $item->currency_code, true)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action"></div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/customers/transactions.js?v=' . version('short')) }}"></script>
@endpush
