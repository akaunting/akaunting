@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 2))

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'customers/invoices', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left box-filter">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('categories[]', $categories, request('categories'), ['id' => 'filter-categories', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::dateRange('invoice_date', trans('invoices.invoice_date'), 'calendar', []) !!}
            {!! Form::select('statuses[]', $statuses, request('statuses'), ['id' => 'filter-statuses', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter hidden-xs">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-invoices">
                <thead>
                <tr>
                    <th class="col-md-2">@sortablelink('invoice_number', trans_choice('general.numbers', 1))</th>
                    <th class="col-md-2 text-right amount-space">@sortablelink('amount', trans('general.amount'))</th>
                    <th class="col-md-3">@sortablelink('invoiced_at', trans('invoices.invoice_date'))</th>
                    <th class="col-md-3">@sortablelink('due_at', trans('invoices.due_date'))</th>
                    <th class="col-md-2">@sortablelink('invoice_status_code', trans_choice('general.statuses', 1))</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $item)
                    @php $paid = $item->paid; @endphp
                    <tr>
                        <td><a href="{{ url('customers/invoices/' . $item->id) }}">{{ $item->invoice_number }}</a></td>
                        <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                        <td>{{ Date::parse($item->invoiced_at)->format($date_format) }}</td>
                        <td>{{ Date::parse($item->due_at)->format($date_format) }}</td>
                        <td><span class="label {{ $item->status->label }}">{{ trans('invoices.status.' . $item->status->code) }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        @include('partials.admin.pagination', ['items' => $invoices, 'type' => 'invoices'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

@push('js')
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/daterangepicker/moment.js') }}"></script>
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@if (language()->getShortCode() != 'en')
<script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
@endif
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $("#filter-categories").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
        });

        $("#filter-statuses").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.statuses', 1)]) }}"
        });
    });
</script>
@endpush
