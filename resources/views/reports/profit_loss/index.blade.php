@extends('layouts.admin')

@section('title', trans('reports.profit_loss'))

@section('new_button')
<span class="new-button"><a href="{{ url('reports/profit-loss') }}?print=1&status={{ request('status') }}&year={{ request('year', $this_year) }}" target="_blank" class="btn btn-success btn-sm"><span class="fa fa-print"></span> &nbsp;{{ trans('general.print') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'reports/profit-loss', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left" style="margin-left: 5px">
            {!! Form::select('year', $years, request('year', $this_year), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('status', $statuses, request('status'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    @include('reports.profit_loss.body')
</div>
<!-- /.box -->
@endsection
