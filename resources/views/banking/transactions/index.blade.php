@extends('layouts.admin')

@section('title', trans_choice('general.transactions', 2))

@section('new_button')
    @can('create-sales-revenues')
        <a href="{{ route('revenues.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_income') }}</a>
    @endcan
    @can('create-purchases-payments')
        <a href="{{ route('payments.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_expense') }}</a>
    @endcan
    <a href="{{ route('import.create', ['banking', 'transactions']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    <a href="{{ route('transactions.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'transactions.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Banking\Transaction" />
                </div>

                {{ Form::bulkActionRowGroup('general.transactions', $bulk_actions, ['group' => 'banking', 'type' => 'transactions']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1">@sortablelink('paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-2 col-lg-1 col-xl-1 d-none d-md-block text-left">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-3 col-xl-3 d-none d-md-block">@sortablelink('description', trans('general.description'))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($transactions as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->contact->name) }}</td>
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1">
                                <a class="col-aka" href="{{ route($item->route_name, $item->route_id) }}">
                                    @date($item->paid_at)
                                </a>
                            </td>
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-md-2 col-lg-1 col-xl-1 d-none d-md-block text-left">{{ $item->type_title }}</td>
                            <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left">{{ $item->category->name }}</td>
                            <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left long-texts">{{ $item->account->name }}</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-3 col-xl-3 d-none d-md-block long-texts">{{ $item->description }}</td>
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
