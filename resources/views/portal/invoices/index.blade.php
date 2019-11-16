@extends('layouts.portal')

@section('title', trans_choice('general.invoices', 2))

@section('content')
     <div class="card">
        <div class="card-header border-bottom-0">
            {!! Form::open([
                'route' => 'portal.invoices.index',
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
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-xs-4 col-sm-4 col-md-3">@sortablelink('invoice_number', trans('invoices.invoice_number'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-sm-3 col-md-3 hidden-sm">@sortablelink('invoiced_at', trans('invoices.invoice_date'))</th>
                        <th class="col-md-2 hidden-md">@sortablelink('due_at', trans('invoices.due_date'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 text-center">@sortablelink('status.name', trans_choice('general.statuses', 1))</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($invoices as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-xs-4 col-sm-4 col-md-3"><a class="text-success" href="{{ route('portal.invoices.show', $item->id) }}">{{ $item->invoice_number }}</a></td>
                            <td class="col-xs-4 col-sm-2 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-sm-3 col-md-3 hidden-sm">@date($item->invoiced_at)</td>
                            <td class="col-md-2 hidden-md">@date($item->due_at)</td>
                            <td class="col-xs-4 col-sm-3 col-md-2 text-center"><span class="badge badge-pill badge-{{ $item->status->label }}">{{ trans('invoices.status.' . $item->status->code) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $invoices, 'type' => 'invoices'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
