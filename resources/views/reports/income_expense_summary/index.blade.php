@extends('layouts.admin')

@section('title', trans('reports.summary.income_expense'))

@section('new_button')
<span class="new-button"><a href="{{ url('reports/income-expense-summary') }}?print=1&status={{ request('status') }}&year={{ request('year', $this_year) }}" target="_blank" class="btn btn-success btn-sm"><span class="fa fa-print"></span> &nbsp;{{ trans('general.print') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header">
        <div class="pull-left" style="margin-left: 5px">
            <a href="{{ url('reports/income-expense-summary') }}?year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == '') bg-green @else bg-default @endif">{{ trans('general.all') }}</span></a>
            <a href="{{ url('reports/income-expense-summary') }}?status=paid&year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == 'paid') bg-green @else bg-default @endif">{{ trans('invoices.paid') }}</span></a>
            <a href="{{ url('reports/income-expense-summary') }}?status=upcoming&year={{ request('year', $this_year) }}"><span class="badge @if (request('status') == 'upcoming') bg-green @else bg-default @endif">{{ trans('general.upcoming') }}</span></a>
        </div>
        {!! Form::open(['url' => 'reports/income-expense-summary', 'role' => 'form', 'method' => 'GET']) !!}
        <div class="pull-right">
            {!! Form::select('year', $years, request('year', $this_year), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    @include('reports.income_expense_summary.body')
</div>
<!-- /.box -->
@endsection
