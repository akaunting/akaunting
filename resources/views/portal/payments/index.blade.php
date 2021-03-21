@extends('layouts.portal')

@section('title', trans_choice('general.payments', 2))

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'route' => 'portal.payments.index',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}

                <div class="row">
                    <div class="col-12 align-items-center">
                        <x-search-string model="App\Models\Portal\Banking\Transaction" />
                    </div>
                </div>

            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-3 col-sm-3">@sortablelink('paid_at', trans('general.date'))</th>
                        <th class="col-xs-3 col-sm-3">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-xs-6 col-sm-3">@sortablelink('payment_method', trans_choice('general.payment_methods', 1))</th>
                        <th class="col-sm-3 d-none d-sm-block">@sortablelink('description', trans('general.description'))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($payments as $item)
                        <tr class="row align-items-center border-top-1 tr-py">
                            <td class="col-xs-3 col-sm-3"><a href="{{ route('portal.payments.show', $item->id) }}">@date($item->paid_at)</a></td>
                            <td class="col-xs-3 col-sm-3">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-xs-6 col-sm-3">{{ $payment_methods[$item->payment_method] }}</td>
                            <td class="col-sm-3 d-none d-sm-block">{{ $item->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $payments])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/payments.js?v=' . version('short')) }}"></script>
@endpush
