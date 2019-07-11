<div class="modal fade add-payment-{{ $rand }}" id="modal-add-payment" style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.payments', 1)]) }}</h4>
            </div>

            <div class="modal-body">
                <div class="modal-message"></div>

                {!! Form::open(['id' => 'form-add-payment', 'role' => 'form', 'class' => 'form-loading-button']) !!}

                <div class="row">
                    {{ Form::textGroup('paid_at', trans('general.date'), 'calendar',['id' => 'paid_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::now()->toDateString()) }}

                    {{ Form::textGroup('amount', trans('general.amount'), 'money', ['required' => 'required', 'autofocus' => 'autofocus'], $bill->grand_total) }}

                    {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, setting('general.default_account')) }}

                    @stack('currency_code_input_start')
                    <div class="form-group col-md-6 required">
                        {!! Form::label('currency_code', trans_choice('general.currencies', 1), ['class' => 'control-label']) !!}
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-exchange"></i></div>
                            {!! Form::text('currency', $currencies[$bill->currency_code], ['id' => 'currency', 'class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
                            {!! Form::hidden('currency_code', $bill->currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                    </div>
                    @stack('currency_code_input_end')

                    {{ Form::textareaGroup('description', trans('general.description')) }}

                    {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('general.default_payment_method')) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o',[]) }}

                    {!! Form::hidden('bill_id', $bill->id, ['id' => 'bill_id', 'class' => 'form-control', 'required' => 'required']) !!}
                </div>

                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <div class="pull-left">
                    {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-add-payment', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}

                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.add-payment-{{ $rand }}#modal-add-payment #amount').focus();

    $(document).ready(function(){
        $('.add-payment-{{ $rand }}#modal-add-payment').modal('show');

        $(".add-payment-{{ $rand }}#modal-add-payment #amount").maskMoney({
            thousands : '{{ $currency->thousands_separator }}',
            decimal : '{{ $currency->decimal_mark }}',
            precision : {{ $currency->precision }},
            allowZero : true,
            @if($currency->symbol_first)
            prefix : '{{ $currency->symbol }}'
            @else
            suffix : '{{ $currency->symbol }}'
            @endif
        });

        $('.add-payment-{{ $rand }}#modal-add-payment #amount').trigger('focusout');

        $('.add-payment-{{ $rand }}#modal-add-payment #paid_at').datepicker({
            format: 'yyyy-mm-dd',
            todayBtn: 'linked',
            weekStart: 1,
            autoclose: true,
            language: '{{ language()->getShortCode() }}'
        });

        $(".add-payment-{{ $rand }}#modal-add-payment #account_id").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
        });

        $(".add-payment-{{ $rand }}#modal-add-payment #payment_method").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)]) }}"
        });
    });

    $(document).on('change', '.add-payment-{{ $rand }}#modal-add-payment  #account_id', function (e) {
        $.ajax({
            url: '{{ url("banking/accounts/currency") }}',
            type: 'GET',
            dataType: 'JSON',
            data: 'account_id=' + $(this).val(),
            success: function(data) {
                $('.add-payment-{{ $rand }}#modal-add-payment  #currency').val(data.currency_name);
                $('.add-payment-{{ $rand }}#modal-add-payment  #currency_code').val(data.currency_code);

                amount = $('.add-payment-{{ $rand }}#modal-add-payment  #amount').maskMoney('unmasked')[0];

                $(".add-payment-{{ $rand }}#modal-add-payment  #amount").maskMoney({
                    thousands : data.thousands_separator,
                    decimal : data.decimal_mark,
                    precision : data.precision,
                    allowZero : true,
                    prefix : (data.symbol_first) ? data.symbol : '',
                    suffix : (data.symbol_first) ? '' : data.symbol
                });

                $('.add-payment-{{ $rand }}#modal-add-payment  #amount').val(amount);

                $('.add-payment-{{ $rand }}#modal-add-payment #amount').focus();
            }
        });
    });

    $(document).on('click', '.add-payment-{{ $rand }} #button-add-payment', function (e) {
        $('.add-payment-{{ $rand }} .help-block').remove();

        $.ajax({
            url: '{{ url("modals/bills/" . $bill->id . "/payment") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $(".add-payment-{{ $rand }} #form-add-payment").serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            beforeSend: function() {
                $('.add-payment-{{ $rand }} #button-add-payment').button('loading');

                $('.add-payment-{{ $rand }}#modal-add-payment .modal-content').append('<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>');
            },
            complete: function() {
                $('.add-payment-{{ $rand }} #button-add-payment').button('reset');
                $('.add-payment-{{ $rand }} #loading').remove();
            },
            success: function(json) {
                if (json['error']) {
                    $('.add-payment-{{ $rand }}#modal-add-payment .modal-message').append('<div class="alert alert-danger">' + json['message'] + '</div>');
                    $('.add-payment-{{ $rand }} div.alert-danger').delay(3000).fadeOut(350);
                }

                if (json['success']) {
                    $('.add-payment-{{ $rand }}#modal-add-payment .modal-message').before('<div class="alert alert-success">' + json['message'] + '</div>');
                    $('.add-payment-{{ $rand }} div.alert-success').delay(3000).fadeOut(350);

                    setTimeout(function(){
                        $(".add-payment-{{ $rand }}#modal-add-payment").modal('hide');

                        location.reload();
                    }, 3000);
                }
            },
            error: function(data){
                var errors = data.responseJSON;

                if (typeof errors !== 'undefined') {
                    if (errors.paid_at) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #paid_at').parent().after('<p class="help-block">' + errors.paid_at + '</p>');
                    }

                    if (errors.amount) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #amount').parent().after('<p class="help-block">' + errors.amount + '</p>');
                    }

                    if (errors.account_id) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #account_id').parent().after('<p class="help-block">' + errors.account_id + '</p>');
                    }

                    if (errors.currency_code) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #currency_code').parent().after('<p class="help-block">' + errors.currency_code + '</p>');
                    }

                    if (errors.category_id) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #category_id').parent().after('<p class="help-block">' + errors.category_id + '</p>');
                    }

                    if (errors.payment_method) {
                        $('.add-payment-{{ $rand }}#modal-add-payment #payment_method').parent().after('<p class="help-block">' + errors.payment_method + '</p>');
                    }
                }
            }
        });
    });
</script>
