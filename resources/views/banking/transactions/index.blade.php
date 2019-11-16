@extends('layouts.admin')

@section('title', trans_choice('general.transactions', 2))

@section('new_button')
    <span><a href="{{ route('import.create', ['banking', 'transactions']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload "></span> &nbsp;{{ trans('import.import') }}</a></span>
    <span><a href="{{ route('transactions.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'url' => 'banking/transactions',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
                <div class="row">
                    <div class="col-12 card-header-search">
                        <span class="table-text hidden-lg">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 hidden-sm">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-sm-2 col-md-2 hidden-sm">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-md-2 hidden-md">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 hidden-sm">@date($item->paid_at)</td>
                            <td class="col-xs-4 col-sm-3 col-md-2">{{ $item->account->name }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-2">{{ trans_choice('general.' . Str::plural($item->type), 1) }}</td>
                            <td class="col-sm-2 col-md-2 hidden-sm">{{ $item->category->name }}</td>
                            <td class="col-md-2 hidden-md">{{ $item->description }}</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $transactions, 'type' => 'transactions'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transactions.js?v=' . version('short')) }}"></script>
@endpush
