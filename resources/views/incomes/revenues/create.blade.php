@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.revenues', 1)]))

@section('content')
<!-- Default box -->
<div class="box box-success">
    {!! Form::open(['url' => 'incomes/revenues', 'files' => true, 'role' => 'form']) !!}

    <div class="box-body">
        {{ Form::textGroup('paid_at', trans('general.date'), 'calendar',['id' => 'paid_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => ''], Date::now()->toDateString()) }}

        {{ Form::textGroup('amount', trans('general.amount'), 'money', ['required' => 'required', 'autofocus' => 'autofocus']) }}

        <div class="form-group col-md-6 form-small">
            {!! Form::label('account_id', trans_choice('general.accounts', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-university"></i></div>
                {!! Form::select('account_id', $accounts, setting('general.accounts', 1), array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)])])) !!}
                <div class="input-group-append">
                    {!! Form::text('currency', $account_currency_code, ['id' => 'currency', 'class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
                    {!! Form::hidden('currency_code', $account_currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                    {!! Form::hidden('currency_rate', '', ['id' => 'currency_rate']) !!}
                </div>
            </div>
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('customer_id', trans_choice('general.customers', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                {!! Form::select('customer_id', $customers, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)])])) !!}
                <span class="input-group-btn">
                    <button type="button" onclick="createCustomer();" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </span>
            </div>
        </div>

        {{ Form::textareaGroup('description', trans('general.description')) }}

        <div class="form-group col-md-6 required {{ $errors->has('category_id') ? 'has-error' : ''}}">
            {!! Form::label('category_id', trans_choice('general.categories', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-folder-open-o"></i></div>
                {!! Form::select('category_id', $categories, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)])])) !!}
                <div class="input-group-btn">
                    <button type="button" onclick="createCategory();" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
        </div>

        {{ Form::recurring('create') }}

        {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('general.default_payment_method')) }}

        {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o', []) }}

        {{ Form::fileGroup('attachment', trans('general.attachment')) }}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        {{ Form::saveButtons('incomes/revenues') }}
    </div>
    <!-- /.box-footer -->

    {!! Form::close() !!}
</div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').trigger('change');

            //Date picker
            $('#paid_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
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
                placeholder : '{{ trans('general.form.no_file_selected') }}'
            });
        });

        $(document).on('change', '#account_id', function (e) {
            $.ajax({
                url: '{{ url("banking/accounts/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'account_id=' + $(this).val(),
                success: function(data) {
                    $('#currency').val(data.currency_code);

                    $('#currency_code').val(data.currency_code);
                    $('#currency_rate').val(data.currency_rate);
                }
            });
        });

        function createCustomer() {
            $('#modal-create-customer').remove();

            modal  = '<div class="modal fade" id="modal-create-customer" style="display: none;">';
            modal += '  <div class="modal-dialog  modal-lg">';
            modal += '      <div class="modal-content">';
            modal += '          <div class="modal-header">';
            modal += '              <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.customers', 1)]) }}</h4>';
            modal += '          </div>';
            modal += '          <div class="modal-body">';
            modal += '              {!! Form::open(['id' => 'form-create-customer', 'role' => 'form']) !!}';
            modal += '              <div class="row">';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="name" class="control-label">{{ trans('general.name') }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-id-card-o"></i></div>';
            modal += '                          <input class="form-control" placeholder="{{ trans('general.name') }}" required="required" name="name" type="text" id="name">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-6">';
            modal += '                      <label for="email" class="control-label">{{ trans('general.email') }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-envelope"></i></div>';
            modal += '                          <input class="form-control" placeholder="{{ trans('general.email') }}" name="email" type="text" id="email">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-6">';
            modal += '                      <label for="tax_number" class="control-label">{{ trans('general.tax_number') }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-percent"></i></div>';
            modal += '                          <input class="form-control" placeholder="{{ trans('general.tax_number') }}" name="tax_number" type="text" id="tax_number">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="email" class="control-label">{{ trans_choice('general.currencies', 1) }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-exchange"></i></div>';
            modal += '                          <select class="form-control" required="required" id="currency_code" name="currency_code">';
            modal += '                              <option value="">{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}</option>';
            @foreach($currencies as $currency_code => $currency_name)
                    modal += '                              <option value="{{ $currency_code }}" {{ (setting('general.default_currency') == $currency_code) ? 'selected' : '' }}>{{ $currency_name }}</option>';
            @endforeach
                    modal += '                          </select>';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-12">';
            modal += '                      <label for="address" class="control-label">{{ trans('general.address') }}</label>';
            modal += '                      <textarea class="form-control" placeholder="{{ trans('general.address') }}" rows="3" name="address" cols="50" id="address"></textarea>';
            modal += '                  </div>';
            modal += '                  {!! Form::hidden('enabled', '1', []) !!}';
            modal += '              </div>';
            modal += '              {!! Form::close() !!}';
            modal += '          </div>';
            modal += '          <div class="modal-footer">';
            modal += '              <div class="pull-left">';
            modal += '              {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-customer', 'class' => 'btn btn-success']) !!}';
            modal += '              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>';
            modal += '              </div>';
            modal += '          </div>';
            modal += '      </div>';
            modal += '  </div>';
            modal += '</div>';

            $('body').append(modal);

            $("#modal-create-customer #currency_code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $('#modal-create-customer').modal('show');
        }

        $(document).on('click', '#button-create-customer', function (e) {
            $('#modal-create-customer .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

            $.ajax({
                url: '{{ url("incomes/customers/customer") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $("#form-create-customer").serialize(),
                beforeSend: function () {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").remove();
                },
                success: function(data) {
                    $('#span-loading').remove();

                    $('#modal-create-customer').modal('hide');

                    $("#customer_id").append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                    $("#customer_id").select2('refresh');
                },
                error: function(error, textStatus, errorThrown) {
                    $('#span-loading').remove();

                    if (error.responseJSON.name) {
                        $("input[name='name']").parent().parent().addClass('has-error');
                        $("input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                    }

                    if (error.responseJSON.email) {
                        $("input[name='email']").parent().parent().addClass('has-error');
                        $("input[name='email']").parent().after('<p class="help-block">' + error.responseJSON.email + '</p>');
                    }

                    if (error.responseJSON.currency_code) {
                        $("select[name='currency_code']").parent().parent().addClass('has-error');
                        $("select[name='currency_code']").parent().after('<p class="help-block">' + error.responseJSON.currency_code + '</p>');
                    }
                }
            });
        });

        function createCategory() {
            $('#modal-create-category').remove();

            modal  = '<div class="modal fade" id="modal-create-category" style="display: none;">';
            modal += '  <div class="modal-dialog  modal-lg">';
            modal += '      <div class="modal-content">';
            modal += '          <div class="modal-header">';
            modal += '              <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}</h4>';
            modal += '          </div>';
            modal += '          <div class="modal-body">';
            modal += '              {!! Form::open(['id' => 'form-create-category', 'role' => 'form']) !!}';
            modal += '              <div class="row">';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="name" class="control-label">{{ trans('general.name') }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-id-card-o"></i></div>';
            modal += '                          <input class="form-control" placeholder="{{ trans('general.name') }}" required="required" name="name" type="text" id="name">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="color" class="control-label">{{ trans('general.color') }}</label>';
            modal += '                      <div  id="category-color-picker" class="input-group colorpicker-component">';
            modal += '                          <div class="input-group-addon"><i></i></div>';
            modal += '                          <input class="form-control" value="#00a65a" placeholder="{{ trans('general.color') }}" required="required" name="color" type="text" id="color">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  {!! Form::hidden('type', 'income', []) !!}';
            modal += '                  {!! Form::hidden('enabled', '1', []) !!}';
            modal += '              </div>';
            modal += '              {!! Form::close() !!}';
            modal += '          </div>';
            modal += '          <div class="modal-footer">';
            modal += '              <div class="pull-left">';
            modal += '              {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-category', 'class' => 'btn btn-success']) !!}';
            modal += '              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>';
            modal += '              </div>';
            modal += '          </div>';
            modal += '      </div>';
            modal += '  </div>';
            modal += '</div>';

            $('body').append(modal);

            $('#category-color-picker').colorpicker();

            $('#modal-create-category').modal('show');
        }

        $(document).on('click', '#button-create-category', function (e) {
            $('#modal-create-category .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 10em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

            $.ajax({
                url: '{{ url("settings/categories/category") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $("#form-create-category").serialize(),
                beforeSend: function () {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").remove();
                },
                success: function(data) {
                    $('#span-loading').remove();

                    $('#modal-create-category').modal('hide');

                    $("#category_id").append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                    $("#category_id").select2('refresh');
                },
                error: function(error, textStatus, errorThrown) {
                    $('#span-loading').remove();

                    if (error.responseJSON.name) {
                        $("input[name='name']").parent().parent().addClass('has-error');
                        $("input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                    }

                    if (error.responseJSON.color) {
                        $("input[name='color']").parent().parent().addClass('has-error');
                        $("input[name='color']").parent().after('<p class="help-block">' + error.responseJSON.color + '</p>');
                    }
                }
            });
        });
    </script>
@endpush
