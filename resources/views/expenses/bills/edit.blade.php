@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.bills', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($bill, ['method' => 'PATCH', 'files' => true, 'url' => ['expenses/bills', $bill->id], 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::selectGroup('vendor_id', trans_choice('general.vendors', 1), 'user', $vendors) }}

            {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies) }}

            {{ Form::textGroup('billed_at', trans('bills.bill_date'), 'calendar', ['id' => 'billed_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::parse($bill->billed_at)->toDateString()) }}

            {{ Form::textGroup('due_at', trans('bills.due_date'), 'calendar', ['id' => 'due_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], Date::parse($bill->due_at)->toDateString()) }}

            {{ Form::textGroup('bill_number', trans('bills.bill_number'), 'file-text-o') }}

            {{ Form::textGroup('order_number', trans('bills.order_number'), 'shopping-cart',[]) }}

            <div class="form-group col-md-12">
                {!! Form::label('items', trans_choice('general.items', 2), ['class' => 'control-label']) !!}
                <div class="table-responsive">
                    <table class="table table-bordered" id="items">
                        <thead>
                            <tr style="background-color: #f9f9f9;">
                                @stack('actions_th_start')
                                <th width="5%"  class="text-center">{{ trans('general.actions') }}</th>
                                @stack('actions_th_end')
                                @stack('name_th_start')
                                <th width="40%" class="text-left">{{ trans('general.name') }}</th>
                                @stack('name_th_end')
                                @stack('quantity_th_start')
                                <th width="5%" class="text-center">{{ trans('bills.quantity') }}</th>
                                @stack('quantity_th_end')
                                @stack('price_th_start')
                                <th width="10%" class="text-right">{{ trans('bills.price') }}</th>
                                @stack('price_th_end')
                                @stack('taxes_th_start')
                                <th width="15%" class="text-right">{{ trans_choice('general.taxes', 1) }}</th>
                                @stack('taxes_th_end')
                                @stack('total_th_start')
                                <th width="10%" class="text-right">{{ trans('bills.total') }}</th>
                                @stack('total_th_end')
                            </tr>
                        </thead>
                        <tbody>
                            @php $item_row = 0; $tax_row = 0; @endphp
                            @if(old('item'))
                                @foreach(old('item') as $old_item)
                                    @php $item = (object) $old_item; @endphp
                                    @include('expenses.bills.item')
                                    @php $item_row++; @endphp
                                @endforeach
                            @else
                                @foreach($bill->items as $item)
                                    @include('expenses.bills.item')
                                    @php $item_row++; @endphp
                                @endforeach
                                @if (empty($bill->items))
                                    @include('expenses.bills.item')
                                @endif
                            @endif
                            @php $item_row++; @endphp
                            @stack('add_item_td_start')
                            <tr id="addItem">
                                <td class="text-center"><button type="button" id="button-add-item" data-toggle="tooltip" title="{{ trans('general.add') }}" class="btn btn-xs btn-primary" data-original-title="{{ trans('general.add') }}"><i class="fa fa-plus"></i></button></td>
                                <td class="text-right" colspan="5"></td>
                            </tr>
                            @stack('add_item_td_end')
                            @stack('sub_total_td_start')
                            <tr id="tr-subtotal">
                                <td class="text-right" colspan="5"><strong>{{ trans('bills.sub_total') }}</strong></td>
                                <td class="text-right"><span id="sub-total">0</span></td>
                            </tr>
                            @stack('sub_total_td_end')
                            @stack('add_discount_td_start')
                            <tr id="tr-discount">
                                <td class="text-right" style="vertical-align: middle;" colspan="5">
                                    <a href="javascript:void(0)" id="discount-text" rel="popover">{{ trans('bills.add_discount') }}</a>
                                </td>
                                <td class="text-right">
                                    <span id="discount-total"></span>
                                    {!! Form::hidden('discount', null, ['id' => 'discount', 'class' => 'form-control text-right']) !!}
                                </td>
                            </tr>
                            @stack('add_discount_td_end')
                            @stack('tax_total_td_start')
                            <tr id="tr-tax">
                                <td class="text-right" colspan="5">
                                    <strong>{{ trans_choice('general.taxes', 1) }}</strong>
                                </td>
                                <td class="text-right"><span id="tax-total">0</span></td>
                            </tr>
                            @stack('tax_total_td_end')
                            @stack('grand_total_td_start')
                            <tr id="tr-total">
                                <td class="text-right" colspan="5"><strong>{{ trans('bills.total') }}</strong></td>
                                <td class="text-right"><span id="grand-total">0</span></td>
                            </tr>
                            @stack('grand_total_td_end')
                        </tbody>
                    </table>
                </div>
            </div>

            {{ Form::textareaGroup('notes', trans_choice('general.notes', 2)) }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder-open-o', $categories) }}

            {{ Form::recurring('edit', $bill) }}

            {{ Form::fileGroup('attachment', trans('general.attachment')) }}

            {{ Form::hidden('vendor_name', old('customer_name', null), ['id' => 'vendor_name']) }}
            {{ Form::hidden('vendor_email', old('vendor_email', null), ['id' => 'vendor_email']) }}
            {{ Form::hidden('vendor_tax_number', old('vendor_tax_number', null), ['id' => 'vendor_tax_number']) }}
            {{ Form::hidden('vendor_phone', old('vendor_phone', null), ['id' => 'vendor_phone']) }}
            {{ Form::hidden('vendor_address', old('vendor_address', null), ['id' => 'vendor_address']) }}
            {{ Form::hidden('currency_rate', old('currency_rate', null), ['id' => 'currency_rate']) }}
            {{ Form::hidden('bill_status_code', old('bill_status_code', null), ['id' => 'bill_status_code']) }}
            {{ Form::hidden('amount', old('amount', null), ['id' => 'amount']) }}
        </div>
        <!-- /.box-body -->

        @permission('update-expenses-bills')
        <div class="box-footer">
            {{ Form::saveButtons('expenses/bills') }}
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
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var focus = false;
        var item_row = '{{ $item_row }}';
        var autocomplete_path = "{{ url('common/items/autocomplete') }}";

        $(document).ready(function() {
            $('#vendor_id').trigger('change');

            itemTableResize();

            $('.input-price').maskMoney({
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

            $('.input-price').trigger('focusout');

            totalItem();

            //Date picker
            $('#billed_at').datepicker({
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            //Date picker
            $('#due_at').datepicker({
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $('.tax-select2').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                language: {
                    noResults: function () {
                        return '<span id="tax-add-new"><i class="fa fa-plus"></i> {{ trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]) }}</span>';
                    }
                }
            });

            $('#vendor_id').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.vendors', 1)]) }}"
            });

            $('#currency_code').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $('#category_id').select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            // Discount popover
            $('a[rel=popover]').popover({
                html: true,
                placement: 'bottom',
                title: '{{ trans('bills.discount') }}',
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
                    html += '           {{ trans('bills.discount_desc') }}';
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

            $('#attachment').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($bill->attachment)
                placeholder : '{{ $bill->attachment->basename }}'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($bill->attachment)
            $.ajax({
                url: '{{ url('uploads/' . $bill->attachment->id . '/show') }}',
                type: 'GET',
                data: {column_name: 'attachment'},
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('.fancy-file').after(json['html']);
                    }
                }
            });
            @endif

            @if(old('item'))
            totalItem();
            @endif
        });

        @permission('delete-common-uploads')
        @if($bill->attachment)
        $(document).on('click', '#remove-attachment', function (e) {
            confirmDelete("#attachment-{!! $bill->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $bill->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
        });
        @endif
        @endpermission

        $(document).on('click', '#button-add-item', function (e) {
            $.ajax({
                url: '{{ url("expenses/bills/addItem") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {item_row: item_row, currency_code : $('#currency_code').val()},
                success: function(json) {
                    if (json['success']) {
                        $('#items tbody #addItem').before(json['html']);
                        //$('[rel=tooltip]').tooltip();

                        $('[data-toggle="tooltip"]').tooltip('hide');

                        $('#item-row-' + item_row + ' .tax-select2').select2({
                            placeholder: {
                                id: '-1', // the value of the option
                                text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                            },
                            escapeMarkup: function (markup) {
                                return markup;
                            },
                            language: {
                                noResults: function () {
                                    return '<span id="tax-add-new"><i class="fa fa-plus-circle"> {{ trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]) }}</span>';
                                }
                            }
                        });

                        var currency = json['data']['currency'];

                        $('#item-price-' + item_row).maskMoney({
                            thousands : currency.thousands_separator,
                            decimal : currency.decimal_mark,
                            precision : currency.precision,
                            allowZero : true,
                            prefix : (currency.symbol_first) ? currency.symbol : '',
                            suffix : (currency.symbol_first) ? '' : currency.symbol
                        });

                        $('#item-price-' + item_row).trigger('focusout');

                        item_row++;
                    }
                }
            });
        });

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
                        data: 'query=' + query + '&type=bill&currency_code=' + $('#currency_code').val(),
                        success: function(data) {
                            return process(data);
                        }
                    });
                },
                afterSelect: function (data) {
                    $('#item-id-' + item_id).val(data.item_id);
                    $('#item-quantity-' + item_id).val('1');
                    $('#item-price-' + item_id).val(data.purchase_price);
                    $('#item-tax-' + item_id).val(data.tax_id);

                    // This event Select2 Stylesheet
                    $('#item-price-' + item_id).trigger('focusout');
                    $('#item-tax-' + item_id).trigger('change');

                    $('#item-total-' + item_id).html(data.total);

                    totalItem();
                }
            });
        });

        $(document).on('click', '#tax-add-new', function(e) {
            tax_name = $('.select2-search__field').val();

            $('body > .select2-container.select2-container--default.select2-container--open').remove();

            $('#modal-create-tax').remove();

            $.ajax({
                url: '{{ url("modals/taxes/create") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {name: tax_name, tax_selector: '.tax-select2'},
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
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

        $(document).on('focusin', '#items .input-price', function(){
            focus = true;
        });

        $(document).on('blur', '#items .input-price', function(){
            if (focus) {
                totalItem();

                focus = false;
            }
        });

        $(document).on('keyup', '#items tbody .form-control', function(){
            if (!$(this).hasClass('input-price')) {
                totalItem();
            }
        });

        $(document).on('change', '#vendor_id', function (e) {
            $.ajax({
                url: '{{ url("expenses/vendors/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'vendor_id=' + $(this).val(),
                success: function(data) {
                    $('#vendor_name').val(data.name);
                    $('#vendor_email').val(data.email);
                    $('#vendor_tax_number').val(data.tax_number);
                    $('#vendor_phone').val(data.phone);
                    $('#vendor_address').val(data.address);

                    $('#currency_code').val(data.currency_code);
                    $('#currency_rate').val(data.currency_rate);

                    $('.input-price').each(function(){
                        input_price_id = $(this).attr('id');
                        input_currency_id = input_price_id.replace('price', 'currency');

                        $('#' + input_currency_id).val(data.currency_code);

                        amount = $(this).maskMoney('unmasked')[0];

                        $(this).maskMoney({
                            thousands : data.thousands_separator,
                            decimal : data.decimal_mark,
                            precision : data.precision,
                            allowZero : true,
                            prefix : (data.symbol_first) ? data.symbol : '',
                            suffix : (data.symbol_first) ? '' : data.symbol
                        });

                        $(this).val(amount);

                        $(this).trigger('focusout');
                    });

                    // This event Select2 Stylesheet
                    $('#currency_code').trigger('change');
                }
            });
        });

        $(document).on('hidden.bs.modal', '#modal-create-tax', function () {
            $('.tax-select2').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                language: {
                    noResults: function () {
                        return '<span id="tax-add-new"><i class="fa fa-plus-circle"></i> {{ trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]) }}</span>';
                    }
                }
            });
        });

        function totalItem() {
            $.ajax({
                url: '{{ url("common/items/totalItem") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#currency_code, #discount input[type=\'number\'], #items input[type=\'text\'], #items input[type=\'number\'], #items input[type=\'hidden\'], #items textarea, #items select').serialize(),
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

                        $('.input-price').each(function(){
                            input_price_id = $(this).attr('id');
                            input_currency_id = input_price_id.replace('price', 'currency');

                            $('#' + input_currency_id).val(data.currency_code);

                            amount = $(this).maskMoney('unmasked')[0];

                            $(this).maskMoney({
                                thousands : data.thousands_separator,
                                decimal : data.decimal_mark,
                                precision : data.precision,
                                allowZero : true,
                                prefix : (data.symbol_first) ? data.symbol : '',
                                suffix : (data.symbol_first) ? '' : data.symbol
                            });

                            $(this).val(amount);

                            $(this).trigger('focusout');
                        });
                    }
                }
            });
        }
    </script>
@endpush
