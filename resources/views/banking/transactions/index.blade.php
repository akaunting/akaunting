@extends('layouts.admin')

@section('title', trans_choice('general.transactions', 2))

@section('new_button')
    @permission('create-sales-revenues')
        <span><a href="{{ route('revenues.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_income') }}</a></span>
    @endpermission
    @permission('create-purchases-payments')
        <span><a href="{{ route('payments.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_expense') }}</a></span>
    @endpermission
    <span><a href="{{ route('import.create', ['banking', 'transactions']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload "></span> &nbsp;{{ trans('import.import') }}</a></span>
    <span><a href="{{ route('transactions.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'transactions.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <akaunting-search
                    :placeholder="'{{ trans('general.search_placeholder') }}'"
                    :options="{{ json_encode([]) }}"
                ></akaunting-search>
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 d-none d-sm-block">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-sm-2 col-md-2 d-none d-sm-block">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th class="col-md-2 d-none d-md-block">@sortablelink('description', trans('general.description'))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $item)
                        <tr class="row align-items-center border-top-1 tr-py">
                            <td class="col-sm-2 col-md-2 d-none d-sm-block">
                                <a href="{{ route($item->route_name, $item->route_id) }}">
                                    @date($item->paid_at)
                                </a>
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-xs-4 col-sm-3 col-md-2">{{ $item->type_title }}</td>
                            <td class="col-sm-2 col-md-2 d-none d-sm-block">{{ $item->category->name }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-2">{{ $item->account->name }}</td>
                            <td class="col-md-2 d-none d-md-block long-texts">{{ $item->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $transactions])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transactions.js?v=' . version('short')) }}"></script>
@endpush
