@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.transfers', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($transfer, [
            'method' => 'PATCH',
            'url' => ['banking/transfers', $transfer->id],
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            {{ Form::selectGroup('from_account_id', trans('transfers.from_account'), 'university', $accounts) }}

            {{ Form::selectGroup('to_account_id', trans('transfers.to_account'), 'university', $accounts) }}

            {{ Form::textGroup('amount', trans('general.amount'), 'money') }}

            {{ Form::textGroup('amount_expected', trans('general.amount'), 'money') }}

            {{ Form::textGroup('transferred_at', trans('general.date'), 'calendar',['id' => 'transferred_at', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => 'yyyy-mm-dd', 'autocomplete' => 'off']) }}

            {{ Form::textareaGroupHalf('description', trans('general.description')) }}

            {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, null) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o', []) }}

            {!! Form::hidden('currency_code_from', $currency_from->code, ['id' => 'currency_code_from']) !!}
            {!! Form::hidden('currency_code_to', $currency_to->code, ['id' => 'currency_code_to']) !!}

            {!! Form::hidden('currency_rate_to', $currency_to->rate, ['id' => 'currency_rate_to']) !!}
            {!! Form::hidden('currency_rate_from', $currency_from->rate, ['id' => 'currency_rate_from']) !!}
        </div>
        <!-- /.box-body -->

        @permission('update-banking-transfers')
        <div class="box-footer">
            {{ Form::saveButtons('banking/transfers') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @if (language()->getShortCode() != 'en')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    @endif
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#amount").maskMoney({
                thousands : '{{ $currency_from->thousands_separator }}',
                decimal : '{{ $currency_from->decimal_mark }}',
                precision : {{ $currency_from->precision }},
                allowZero : true,
                @if($currency_from->symbol_first)
                prefix : '{{ $currency_from->symbol }}'
                @else
                suffix : '{{ $currency_from->symbol }}'
                @endif
            });

            $("#amount").focusout();

            $("#amount_expected").maskMoney({
                thousands : '{{ $currency_to->thousands_separator }}',
                decimal : '{{ $currency_to->decimal_mark }}',
                precision : {{ $currency_to->precision }},
                allowZero : true,
                @if($currency_to->symbol_first)
                prefix : '{{ $currency_to->symbol }}'
                @else
                suffix : '{{ $currency_to->symbol }}'
                @endif
            });

            $("#amount_expected").focusout();

            //Date picker
            $('#transferred_at').datepicker({
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $("#from_account_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
            });

            $("#to_account_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
            });

            $("#payment_method").select2({
                placeholder: "{{ trans_choice('general.payment_methods', 1) }}"
            });
        });

        $(document).on('change', '#from_account_id', function (e) {
            $.ajax({
                url: '{{ url("banking/accounts/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'account_id=' + $(this).val(),
                success: function(data) {
                    $('#currency_from').val(data.currency_code);

                    $('#currency_code_from').val(data.currency_code);
                    $('#currency_rate_from').val(data.currency_rate);

                    amount = $('#amount').maskMoney('unmasked')[0];

                    $("#amount").maskMoney({
                        thousands : data.thousands_separator,
                        decimal : data.decimal_mark,
                        precision : data.precision,
                        allowZero : true,
                        prefix : (data.symbol_first) ? data.symbol : '',
                        suffix : (data.symbol_first) ? '' : data.symbol
                    });

                    $('#amount').val(amount);

                    $('#amount').trigger('focus');
                }
            });
        });

        $(document).on('change', '#to_account_id', function (e) {
            $.ajax({
                url: '{{ url("banking/accounts/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'account_id=' + $(this).val(),
                success: function(data) {
                    $('#currency_to').val(data.currency_code);

                    $('#currency_code_to').val(data.currency_code);
                    $('#currency_rate_to').val(data.currency_rate);

                    amount = $('#amount_expected').maskMoney('unmasked')[0];

                    $("#amount_expected").maskMoney({
                        thousands : data.thousands_separator,
                        decimal : data.decimal_mark,
                        precision : data.precision,
                        allowZero : true,
                        prefix : (data.symbol_first) ? data.symbol : '',
                        suffix : (data.symbol_first) ? '' : data.symbol
                    });

                    $('#amount_expected').val(amount);

                    $('#amount').trigger('focus');
                }
            });
        });

    </script>
@endpush
