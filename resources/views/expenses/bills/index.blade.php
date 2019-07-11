@extends('layouts.admin')

@section('title', trans_choice('general.bills', 2))

@section('new_button')
@permission('create-expenses-bills')
<span class="new-button"><a href="{{ url('expenses/bills/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
<span><a href="{{ url('common/import/expenses/bills') }}" class="btn btn-default btn-sm"><span class="fa fa-download"></span> &nbsp;{{ trans('import.import') }}</a></span>
@endpermission
<span><a href="{{ route('bills.export', request()->input()) }}" class="btn btn-default btn-sm"><span class="fa fa-upload"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'expenses/bills', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left box-filter">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('vendors[]', $vendors, request('vendors'), ['id' => 'filter-vendors', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::select('categories[]', $categories, request('categories'), ['id' => 'filter-categories', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::dateRange('bill_date', trans('bills.bill_date'), 'calendar', []) !!}
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
            <table class="table table-striped table-hover" id="tbl-bills">
                <thead>
                    <tr>
                        <th class="col-md-2">@sortablelink('bill_number', trans_choice('general.numbers', 1))</th>
                        <th class="col-md-2">@sortablelink('vendor_name', trans_choice('general.vendors', 1))</th>
                        <th class="col-md-2 text-right amount-space">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-2">@sortablelink('billed_at', trans('bills.bill_date'))</th>
                        <th class="col-md-2">@sortablelink('due_at', trans('bills.due_date'))</th>
                        <th class="col-md-1">@sortablelink('bill_status_code', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bills as $item)
                    @php $paid = $item->paid; @endphp
                    <tr>
                        <td><a href="{{ url('expenses/bills/' . $item->id . ' ') }}">{{ $item->bill_number }}</a></td>
                        <td>{{ $item->vendor_name }}</td>
                        <td class="text-right amount-space">@money($item->amount, $item->currency_code, true)</td>
                        <td>{{ Date::parse($item->billed_at)->format($date_format) }}</td>
                        <td>{{ Date::parse($item->due_at)->format($date_format) }}</td>
                        <td><span class="label {{ $item->status->label }}">{{ trans('bills.status.' . $item->status->code) }}</span></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ url('expenses/bills/' . $item->id) }}">{{ trans('general.show') }}</a></li>
                                    @if (!$item->reconciled)
                                    <li><a href="{{ url('expenses/bills/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @endif
                                    @permission('create-expenses-bills')
                                    <li class="divider"></li>
                                    <li><a href="{{ url('expenses/bills/' . $item->id . '/duplicate') }}">{{ trans('general.duplicate') }}</a></li>
                                    @endpermission
                                    @permission('delete-expenses-bills')
                                    @if (!$item->reconciled)
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'expenses/bills') !!}</li>
                                    @endif
                                    @endpermission
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        @include('partials.admin.pagination', ['items' => $bills, 'type' => 'bills'])
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

        $("#filter-vendors").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.vendors', 1)]) }}"
        });

        $("#filter-statuses").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.statuses', 1)]) }}"
        });
    });
</script>
@endpush

