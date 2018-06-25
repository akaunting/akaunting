@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.invoices', 1)]))

@section('content')
<!-- Default box -->
<div class="box box-success">
    {!! Form::open(['url' => 'incomes/invoices', 'files' => true, 'role' => 'form']) !!}

    <div class="box-body">
        <div class="form-group col-md-6 required {{ $errors->has('customer_id') ? 'has-error' : ''}}">
            {!! Form::label('customer_id', trans_choice('general.customers', 1), ['class' => 'control-label']) !!}
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                {!! Form::select('customer_id', $customers, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)])])) !!}
                <div class="input-group-btn">
                    <button type="button" onclick="createCustomer();" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            {!! $errors->first('customer_id', '<p class="help-block">:message</p>') !!}
        </div>

        {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies, setting('general.default_currency')) }}

        {{ Form::textGroup('invoiced_at', trans('invoices.invoice_date'), 'calendar',['id' => 'invoiced_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy/mm/dd\'', 'data-mask' => ''], Date::now()->toDateString()) }}

        {{ Form::textGroup('due_at', trans('invoices.due_date'), 'calendar',['id' => 'due_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy/mm/dd\'', 'data-mask' => '']) }}

        {{ Form::textGroup('invoice_number', trans('invoices.invoice_number'), 'file-text-o', ['required' => 'required'], $number) }}

        {{ Form::textGroup('order_number', trans('invoices.order_number'), 'shopping-cart', []) }}

        <div class="form-group col-md-12">
            {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}
            <div class="table-responsive">
                <table class="table table-bordered" id="items">
                    <thead>
                        <tr style="background-color: #f9f9f9;">
                            <th width="5%"  class="text-center">{{ trans('general.actions') }}</th>
                            <th width="40%" class="text-left">{{ trans('general.name') }}</th>
                            <th width="5%" class="text-center">{{ trans('invoices.quantity') }}</th>
                            <th width="10%" class="text-right">{{ trans('invoices.price') }}</th>
                            <th width="15%" class="text-right">{{ trans_choice('general.taxes', 1) }}</th>
                            <th width="10%" class="text-right">{{ trans('invoices.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $item_row = 0; ?>
                        <tr id="item-row-{{ $item_row }}">
                            <td class="text-center" style="vertical-align: middle;">
                                <button type="button" onclick="$(this).tooltip('destroy'); $('#item-row-{{ $item_row }}').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                            </td>
                            <td>
                                <input class="form-control typeahead" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('invoices.item_name', 1)]) }}" name="item[{{ $item_row }}][name]" type="text" id="item-name-{{ $item_row }}" autocomplete="off">
                                <input name="item[{{ $item_row }}][item_id]" type="hidden" id="item-id-{{ $item_row }}">
                            </td>
                            <td>
                                <input class="form-control text-center" required="required" name="item[{{ $item_row }}][quantity]" type="text" id="item-quantity-{{ $item_row }}">
                            </td>
                            <td>
                                <input class="form-control text-right" required="required" name="item[{{ $item_row }}][price]" type="text" id="item-price-{{ $item_row }}">
                            </td>
                            <td>
                                {!! Form::select('item[' . $item_row . '][tax_id]', $taxes, setting('general.default_tax'), ['id'=> 'item-tax-'. $item_row, 'class' => 'form-control tax-select2', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)])]) !!}
                            </td>
                            <td class="text-right" style="vertical-align: middle;">
                                <span id="item-total-{{ $item_row }}">0</span>
                            </td>
                        </tr>
                        <?php $item_row++; ?>
                        <tr id="addItem">
                            <td class="text-center"><button type="button" onclick="addItem();" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                            <td class="text-right" colspan="5"></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans('invoices.sub_total') }}</strong></td>
                            <td class="text-right"><span id="sub-total">0</span></td>
                        </tr>
                        <tr>
                            <td class="text-right" style="vertical-align: middle;" colspan="5">
                                <a href="javascript:void(0)" id="discount-text" rel="popover">{{ trans('invoices.add_discount') }}</a>
                            </td>
                            <td class="text-right">
                                <span id="discount-total"></span>
                                {!! Form::hidden('discount', null, ['id' => 'discount', 'class' => 'form-control text-right']) !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans_choice('general.taxes', 1) }}</strong></td>
                            <td class="text-right"><span id="tax-total">0</span></td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5"><strong>{{ trans('invoices.total') }}</strong></td>
                            <td class="text-right"><span id="grand-total">0</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{ Form::textareaGroup('notes', trans_choice('general.notes', 2)) }}

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

        {{ Form::fileGroup('attachment', trans('general.attachment')) }}

        {{ Form::hidden('customer_name', '', ['id' => 'customer_name']) }}
        {{ Form::hidden('customer_email', '', ['id' => 'customer_email']) }}
        {{ Form::hidden('customer_tax_number', '', ['id' => 'customer_tax_number']) }}
        {{ Form::hidden('customer_phone', '', ['id' => 'customer_phone']) }}
        {{ Form::hidden('customer_address', '', ['id' => 'customer_address']) }}
        {{ Form::hidden('currency_rate', '', ['id' => 'currency_rate']) }}
        {{ Form::hidden('invoice_status_code', 'draft', ['id' => 'invoice_status_code']) }}
        {{ Form::hidden('amount', '0', ['id' => 'amount']) }}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        {{ Form::saveButtons('incomes/invoices') }}
    </div>
    <!-- /.box-footer -->

    {!! Form::close() !!}
</div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var item_row = '{{ $item_row }}';

        function addItem() {
            html  = '<tr id="item-row-' + item_row + '">';
            html += '  <td class="text-center" style="vertical-align: middle;">';
            html += '      <button type="button" onclick="$(this).tooltip(\'destroy\'); $(\'#item-row-' + item_row + '\').remove(); totalItem();" data-toggle="tooltip" title="{{ trans('general.delete') }}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>';
            html += '  </td>';
            html += '  <td>';
            html += '      <input class="form-control typeahead" required="required" placeholder="{{ trans('general.form.enter', ['field' => trans_choice('invoices.item_name', 1)]) }}" name="item[' + item_row + '][name]" type="text" id="item-name-' + item_row + '" autocomplete="off">';
            html += '      <input name="item[' + item_row + '][item_id]" type="hidden" id="item-id-' + item_row + '">';
            html += '  </td>';
            html += '  <td>';
            html += '      <input class="form-control text-center" required="required" name="item[' + item_row + '][quantity]" type="text" id="item-quantity-' + item_row + '">';
            html += '  </td>';
            html += '  <td>';
            html += '      <input class="form-control text-right" required="required" name="item[' + item_row + '][price]" type="text" id="item-price-' + item_row + '">';
            html += '  </td>';
            html += '  <td>';
            html += '      <select class="form-control tax-select2" name="item[' + item_row + '][tax_id]" id="item-tax-' + item_row + '">';
            html += '         <option selected="selected" value="">{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}</option>';
            @foreach($taxes as $tax_key => $tax_value)
            html += '         <option value="{{ $tax_key }}">{{ $tax_value }}</option>';
            @endforeach
            html += '      </select>';
            html += '  </td>';
            html += '  <td class="text-right" style="vertical-align: middle;">';
            html += '      <span id="item-total-' + item_row + '">0</span>';
            html += '  </td>';

            $('#items tbody #addItem').before(html);
            //$('[rel=tooltip]').tooltip();

            $('[data-toggle="tooltip"]').tooltip('hide');

            $('#item-row-' + item_row + ' .tax-select2').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            item_row++;
        }

        $(document).ready(function(){
            //Date picker
            $('#invoiced_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            //Date picker
            $('#due_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $(".tax-select2").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            $("#customer_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)]) }}"
            });

            $("#currency_code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#attachment').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '{{ trans('general.form.no_file_selected') }}'
            });

            var autocomplete_path = "{{ url('common/items/autocomplete') }}";

            $(document).on('click', '.form-control.typeahead', function() {
                input_id = $(this).attr('id').split('-');

                item_id = parseInt(input_id[input_id.length-1]);

                $(this).typeahead({
                    minLength: 3,
                    displayText:function (data) {
                        return data.name + ' (' + data.sku + ')';
                    },
                    source: function (query, process) {
                        $.ajax({
                            url: autocomplete_path,
                            type: 'GET',
                            dataType: 'JSON',
                            data: 'query=' + query + '&type=invoice&currency_code=' + $('#currency_code').val(),
                            success: function(data) {
                                return process(data);
                            }
                        });
                    },
                    afterSelect: function (data) {
                        $('#item-id-' + item_id).val(data.item_id);
                        $('#item-quantity-' + item_id).val('1');
                        $('#item-price-' + item_id).val(data.sale_price);
                        $('#item-tax-' + item_id).val(data.tax_id);

                        // This event Select2 Stylesheet
                        $('#item-tax-' + item_id).trigger('change');

                        $('#item-total-' + item_id).html(data.total);

                        totalItem();
                    }
                });
            });

            $('a[rel=popover]').popover({
                html: 'true',
                placement: 'bottom',
                title: '{{ trans('invoices.discount') }}',
                content: function () {
                    html  = '<div class="discount box-body">';
                    html += '    <div class="col-md-6">';
                    html += '        <div class="input-group" id="input-discount">';
                    html += '            {!! Form::number('pre-discount', null, ['id' => 'pre-discount', 'class' => 'form-control text-right']) !!}';
                    html += '            <div class="input-group-addon"><i class="fa fa-percent"></i></div>';
                    html += '        </div>';
                    html += '    </div>';
                    html += '    <div class="col-md-6">';
                    html += '        <div class="discount-description">';
                    html += '           {{ trans('invoices.discount_desc') }}';
                    html += '        </div>';
                    html += '    </div>';
                    html += '</div>';
                    html += '<div class="discount box-footer">';
                    html += '    <div class="col-md-12">';
                    html += '        <div class="form-group no-margin">';
                    html += '            {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' => 'save-discount','class' => 'btn btn-success']) !!}';
                    html += '            <a href="javascript:void(0)" id="cancel-discount" class="btn btn-default"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</a>';
                    html += '       </div>';
                    html += '    </div>';
                    html += '</div>';

                    return html;
                }
            });

            $(document).on('keyup', '#pre-discount', function(e){
                e.preventDefault();

                $('#discount').val($(this).val());

                totalItem();
            });

            $(document).on('click', '#save-discount', function(){
                $('a[rel=popover]').trigger('click');
            });

            $(document).on('click', '#cancel-discount', function(){
                $('#discount').val('');

                totalItem();

                $('a[rel=popover]').trigger('click');
            });

            $(document).on('change', '#currency_code, #items tbody select', function(){
                totalItem();
            });

            $(document).on('keyup', '#items tbody .form-control', function(){
                totalItem();
            });

            $(document).on('change', '#customer_id', function (e) {
                $.ajax({
                    url: '{{ url("incomes/customers/currency") }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'customer_id=' + $(this).val(),
                    success: function(data) {
                        $('#customer_name').val(data.name);
                        $('#customer_email').val(data.email);
                        $('#customer_tax_number').val(data.tax_number);
                        $('#customer_phone').val(data.phone);
                        $('#customer_address').val(data.address);
                        $('#currency_code').val(data.currency_code);
                        $('#currency_rate').val(data.currency_rate);

                        // This event Select2 Stylesheet
                        $('#currency_code').trigger('change');
                    }
                });
            });
        });

        function totalItem() {
            $.ajax({
                url: '{{ url("common/items/totalItem") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#currency_code, #discount input[type=\'number\'], #items input[type=\'text\'],#items input[type=\'number\'],#items input[type=\'hidden\'], #items textarea, #items select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        $.each( data.items, function( key, value ) {
                            $('#item-total-' + key).html(value);
                        });

                        $('#discount-text').text(data.discount_text);

                        $('#sub-total').html(data.sub_total);
                        $('#discount-total').html(data.discount_total);
                        $('#tax-total').html(data.tax_total);
                        $('#grand-total').html(data.grand_total);
                    }
                }
            });
        }

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
            modal += '                          <input class="form-control" placeholder="{{ trans('general.email') }}" required="required" name="email" type="text" id="email">';
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
                    $('#customer_id').trigger('change');

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
