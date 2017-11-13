@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.revenues', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($revenue, [
            'method' => 'PATCH',
            'files' => true,
            'url' => ['incomes/revenues', $revenue->id],
            'role' => 'form'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('paid_at', trans('general.date'), 'calendar',['id' => 'paid_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => ''],Date::now()->toDateString()) }}

            {{ Form::textGroup('amount', trans('general.amount'), 'money', ['required' => 'required', 'autofocus' => 'autofocus']) }}

            {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('general.accounts', 1)) }}

            <div class="form-group col-md-6 {{ $errors->has('currency_code') ? 'has-error' : ''}}">
                {!! Form::label('currency_code', trans_choice('general.currencies', 1), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-exchange"></i></div>
                    {!! Form::text('currency', $currencies[$account_currency_code], ['id' => 'currency', 'class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
                    {!! Form::hidden('currency_code', $account_currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('currency_code', '<p class="help-block">:message</p>') !!}
            </div>

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder-open-o', $categories) }}

            {{ Form::selectGroup('customer_id', trans_choice('general.customers', 1), 'user', $customers, null, []) }}

            {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, null) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o',[]) }}

            {{ Form::fileGroup('attachment', trans('general.attachment')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-incomes-revenues')
        <div class="box-footer">
            {{ Form::saveButtons('incomes/revenues') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            //Date picker
            $('#paid_at').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $("#account_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $("#customer_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)]) }}"
            });

            $("#payment_method").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)]) }}"
            });

            $('#attachment').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '<?php echo $revenue->attachment; ?>'
            });

            $(document).on('change', '#account_id', function (e) {
                $.ajax({
                    url: '{{ url("settings/currencies/currency") }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'account_id=' + $(this).val(),
                    success: function(data) {
                        $('#currency').val(data.currency_name);
                        $('#currency_code').val(data.currency_code);
                    }
                });
            });
        });
    </script>
@endpush
