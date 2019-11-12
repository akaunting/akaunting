@extends('layouts.admin')

@section('title', trans('reports.summary.income'))

@section('new_button')
<span class="new-button"><a href="{{ url($print_url) }}" target="_blank" class="btn btn-success btn-sm"><span class="fa fa-print"></span> &nbsp;{{ trans('general.print') }}</a></span>
@endsection

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'reports/income-summary', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left box-filter">
            {!! Form::select('year', $years, request('year', $this_year), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('status', $statuses, request('status'), ['class' => 'form-control input-filter input-sm']) !!}
            {!! Form::select('accounts[]', $accounts, request('accounts'), ['id' => 'filter-accounts', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::select('customers[]', $customers, request('customers'), ['id' => 'filter-customers', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::select('categories[]', $categories, request('categories'), ['id' => 'filter-categories', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        {!! Form::close() !!}
    </div>

    @include('reports.income_summary.body')
</div>
<!-- /.box -->
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $("#filter-accounts").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
        });

        $("#filter-customers").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)]) }}"
        });

        $("#filter-categories").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
        });
    });
</script>
@endpush