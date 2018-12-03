@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.customers', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'incomes/customers', 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

            {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

            {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies, setting('general.default_currency')) }}

            {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

            {{ Form::textGroup('website', trans('general.website'), 'globe', []) }}

            {{ Form::textareaGroup('address', trans('general.address')) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o', []) }}


            @stack('create_user_input_start')
            <div id="customer-create-user" class="form-group col-md-12 margin-top">
                <strong>{{ trans('customers.allow_login') }}</strong> &nbsp;  {{ Form::checkbox('create_user', '1', null, ['id' => 'create_user']) }}
            </div>
            @stack('create_user_input_end')
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('incomes/customers') }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/iCheck/square/green.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function(){
            $('#enabled_1').trigger('click');

            $('#name').focus();

            $("#currency_code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $('#create_user').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });

            $('#create_user').on('ifClicked', function (event) {
                $('input[name="user_id"]').remove();

                if ($(this).prop('checked')) {
                    $('.col-md-6.password').remove();

                    $('input[name="email"]').parent().parent().removeClass('has-error');
                    $('input[name="email"]').parent().parent().find('.help-block').remove();
                } else {
                    var email = $('input[name="email"]').val();

                    if (!email) {
                        $('input[name="email"]').parent().parent().removeClass('has-error');
                        $('input[name="email"]').parent().parent().find('.help-block').remove();

                        $('input[name="email"]').parent().parent().addClass('has-error');
                        $('input[name="email"]').parent().after('<p class="help-block">{{ trans('validation.required', ['attribute' => 'email']) }}</p>');
                        $('input[name="email"]').focus();

                        unselect();

                        return false;
                    }

                    $.ajax({
                        url: '{{ url("auth/users/autocomplete") }}',
                        type: 'GET',
                        dataType: 'JSON',
                        data: {column: 'email', value: email},
                        beforeSend: function() {
                            $('.iCheck-helper').parent().after('<i class="fa fa-spinner fa-pulse fa-fw loading" style="margin-left: 10px;"></i>');

                            $('input[name="email"]').parent().parent().removeClass('has-error');
                            $('input[name="email"]').parent().parent().find('.help-block').remove();

                            $('.box-footer .btn').attr('disabled', true);
                        },
                        success: function(json) {
                            if (json['errors']) {
                                if (json['data']) {
                                    $('input[name="email"]').parent().parent().addClass('has-error');
                                    $('input[name="email"]').parent().after('<p class="help-block">' + json['data'] + '</p>');
                                    $('input[name="email"]').focus();

                                    return false;
                                }

                                fields = [];

                                fields[0] = 'password';
                                fields[1] = 'password_confirmation';

                                $.ajax({
                                    url: '{{ url("incomes/customers/field") }}',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: {fields: fields},
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    complete: function() {
                                        $('.box-footer .btn').attr('disabled', false);
                                        $('.loading').remove();
                                    },
                                    success: function(json) {
                                        $('#customer-create-user').after(json['html']);
                                    }
                                });
                            }

                            if (json['success']) {
                                unselect();
                                $('input[name="email"]').parent().parent().addClass('has-error');
                                $('input[name="email"]').parent().after('<p class="help-block">{{ trans('customers.error.email') }}</p>');
                                $('input[name="email"]').focus();

                                $('.box-footer .btn').attr('disabled', false);
                                $('.loading').remove();

                                return false;
                            }
                        }
                    });
                }
            });
        });

        function unselect() {
            setTimeout(function(){
                $('#create_user').iCheck('uncheck');
            }, 550);
        }
    </script>
@endpush
