@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
<!-- Default box -->
<div class="box box-solid">
    <div class="box-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/companies') }}" type="button" class="btn btn-default btn-circle">1</a>
                    <p><small>{{ trans_choice('general.companies', 1) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-2" type="button" class="btn btn-success btn-circle">2</a>
                    <p><small>{{ trans_choice('general.currencies', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <button type="button" class="btn btn-default btn-circle" disabled="disabled">3</button>
                    <p><small>{{ trans_choice('general.taxes', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <button type="button" class="btn btn-default btn-circle" disabled="disabled">4</button>
                    <p><small>{{ trans_choice('general.finish', 1) }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div id="wizard-loading"></div>

    <div class="box-header with-border">
        <h3 class="box-title">{{ trans_choice('general.currencies', 2) }}</h3>
        <span class="new-button"><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/create') }}" class="btn btn-success btn-sm currency-create"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-currencies">
                <thead>
                <tr>
                    <th class="col-md-4">@sortablelink('name', trans('general.name'))</th>
                    <th class="col-md-3 hidden-xs">@sortablelink('code', trans('currencies.code'))</th>
                    <th class="col-md-2">@sortablelink('rate', trans('currencies.rate'))</th>
                    <th class="col-md-2 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                    <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($currencies as $item)
                    <tr id="currency-{{ $item->id }}" data-href="{{ url('wizard/currencies/' . $item->id . '/delete') }}">
                        <td class="currency-name"><a href="javascript:void(0);" data-id="{{ $item->id }}" data-href="{{ url('wizard/currencies/' . $item->id . '/edit') }}" class="currency-edit">{{ $item->name }}</a></td>
                        <td class="currency-code hidden-xs">{{ $item->code }}</td>
                        <td class="currency-rate">{{ $item->rate }}</td>
                        <td class="currency-status hidden-xs">
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td class="currency-action text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);" data-id="{{ $item->id }}" data-href="{{ url('wizard/currencies/' . $item->id . '/edit') }}" class="currency-edit">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                        <li><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/disable') }}" class="currency-disable">{{ trans('general.disable') }}</a></li>
                                    @else
                                        <li><a href="javascript:void(0);" data-href="{{ url('wizard/currencies/' . $item->id . '/enable') }}" class="currency-enable">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-settings-currencies')
                                    <li class="divider"></li>
                                    <li>
                                        {!! Form::button(trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'delete-link',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmCurrency("' . '#currency-' . $item->id . '", "' . trans_choice('general.currencies', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->name . '</strong>', 'type' => mb_strtolower(trans_choice('general.currencies', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                        )) !!}
                                    </li>
                                    @endpermission
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            <div class="form-group no-margin">
                <a href="{{ url('wizard/taxes') }}" id="wizard-skip" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
            </div>
        </div>
    </div>
    <!-- /.box-footer -->
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var text_yes = '{{ trans('general.yes') }}';
    var text_no = '{{ trans('general.no') }}';

    $(document).on('click', '.currency-create', function (e) {
        $('#currency-create').remove();
        $('#currency-edit').remove();

        data_href = $(this).data('href');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $('#tbl-currencies tbody').append(json['html']);

                    $("#code").select2({
                        placeholder: "{{ trans('general.form.select.field', ['field' => trans('currencies.code')]) }}"
                    });

                    $('.currency-enabled-radio-group #enabled_1').trigger('click');

                    $('#name').focus();
                }
            }
        });
    });

    $(document).on('click', '.currency-submit', function (e) {
        $(this).html('<span class="fa fa-spinner fa-pulse"></span>');
        $('.help-block').remove();

        data_href = $(this).data('href');

        $.ajax({
            url: '{{ url("wizard/currencies") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#tbl-currencies input[type=\'number\'], #tbl-currencies input[type=\'text\'], #tbl-currencies input[type=\'radio\'], #tbl-currencies input[type=\'hidden\'], #tbl-currencies textarea, #tbl-currencies select').serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(json) {
                $('.currency-submit').html('<span class="fa fa-save"></span>');

                if (json['success']) {
                    currency = json['data'];
                    $('#currency-create').remove();
                    $('#currency-edit').remove();

                    html  = '<tr id="currency-' + currency.id + '" data-href="wizard/currencies/' + currency.id + '/delete">';
                    html += '   <td class="currency-name">';
                    html += '       <a href="javascript:void(0);" data-id="' + currency.id + '" data-href="wizard/currencies/' + currency.id + '/edit" class="currency-edit">' + currency.name + '</a>';
                    html += '   </td>';
                    html += '   <td class="currency-code hidden-xs">' + currency.code + '</td>';
                    html += '   <td class="currency-rate">' + currency.rate + '</td>';
                    html += '   <td class="currency-status hidden-xs">';
                    html += '       <span class="label label-success">Enabled</span>';
                    html += '   </td>';
                    html += '   <td class="currency-action text-center">';
                    html += '       <div class="btn-group">';
                    html += '           <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">';
                    html += '               <i class="fa fa-ellipsis-h"></i>';
                    html += '           </button>';
                    html += '           <ul class="dropdown-menu dropdown-menu-right">';
                    html += '               <li><a href="javascript:void(0);" data-id="' + currency.id + '" data-href="wizard/currencies/' + currency.id + '/edit" class="currency-edit">{{ trans('general.edit') }}</a></li>';
                    html += '               <li><a href="javascript:void(0);" data-href="wizard/currencies/' + currency.id + '/disable" class="currency-disable">{{ trans('general.disable') }}</a></li>';
                    html += '               <li class="divider"></li>';
                    html += '               <li>';
                    html += '                   <button type="button" class="delete-link" title="{{ trans('general.delete') }}" onclick="confirmCurrency("#currency-' + currency.id + '", "{{ trans_choice('general.currencies', 2) }}", "{{ trans('general.delete_confirm', ['name' => '<strong>' . $item->name . '</strong>', 'type' => mb_strtolower(trans_choice('general.currencies', 1))]) }}", "{{ trans('general.cancel') }}", "{{ trans('general.delete') }}")">{{ trans('general.delete') }}</button>';
                    html += '               </li>';
                    html += '           </ul>';
                    html += '       </div>';
                    html += '   </td>';
                    html += '</tr>';

                    $('#tbl-currencies tbody').append(html);
                }
            },
            error: function(data){
                $('.currency-submit').html('<span class="fa fa-save"></span>');

                var errors = data.responseJSON;

                if (typeof errors !== 'undefined') {
                    if (errors.name) {
                        $('#tbl-currencies #name').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.name + '</p>');
                    }

                    if (errors.code) {
                        $('#tbl-currencies #code').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.code + '</p>');
                    }

                    if (errors.rate) {
                        $('#tbl-currencies #rate').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.rate + '</p>');
                    }
                }
            }
        });
    });

    $(document).on('click', '.currency-edit', function (e) {
        $('#currency-create').remove();
        $('#currency-edit').remove();

        data_href = $(this).data('href');
        data_id = $(this).data('id');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $('#currency-' + data_id).after(json['html']);
                    $('#enabled_1').trigger('click');
                    $('#name').focus();

                    $("#code").select2({
                        placeholder: "{{ trans('general.form.select.field', ['field' => trans('currencies.code')]) }}"
                    });

                    $('.currency-enabled-radio-group #enabled_1').trigger();
                }
            }
        });
    });

    $(document).on('click', '.currency-updated', function (e) {
        $(this).html('<span class="fa fa-spinner fa-pulse"></span>');
        $('.help-block').remove();

        data = $('#tbl-currencies input[type=\'number\'], #tbl-currencies input[type=\'text\'], #tbl-currencies input[type=\'radio\'], #tbl-currencies input[type=\'hidden\'], #tbl-currencies textarea, #tbl-currencies select').serialize();
        data_href = $(this).data('href');
        data_id = $(this).data('id');

        $.ajax({
            url: data_href,
            type: 'PATCH',
            dataType: 'JSON',
            data: data,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(json) {
                $('.currency-updated').html('<span class="fa fa-save"></span>');

                if (json['success']) {
                    $('#currency-' + data_id + ' .currency-name a').text($('#currency-edit #name').val());
                    $('#currency-' + data_id + ' .currency-code').text($('#currency-edit #code').val());
                    $('#currency-' + data_id + ' .currency-rate').text($('#currency-edit #rate').val());

                    if ($('#currency-edit #enabled').val()) {
                        $('#currency-' + data_id + ' .currency-status').html('<span class="label label-success">{{ trans('general.enabled') }}</span>');
                    } else {
                        $('#currency-' + data_id + ' .currency-status').html('<span class="label label-danger">{{ trans('general.disabled') }}</span>');
                    }

                    $('#currency-create').remove();
                    $('#currency-edit').remove();
                }
            },
            error: function(data){
                $('.currency-updated').html('<span class="fa fa-save"></span>');

                var errors = data.responseJSON;

                if (typeof errors !== 'undefined') {
                    if (errors.name) {
                        $('#tbl-currencies #name').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.name + '</p>');
                    }

                    if (errors.code) {
                        $('#tbl-currencies #code').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.code + '</p>');
                    }

                    if (errors.rate) {
                        $('#tbl-currencies #rate').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.rate + '</p>');
                    }
                }
            }
        });
    });

    $(document).on('click', '.currency-disable', function (e) {
        data_href = $(this).data('href');

        currency_tr = $(this).parent().parent().parent().parent().parent();

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    currency_tr.find('.currency-status').html('<span class="label label-danger">{{ trans('general.disabled') }}</span>');
                }
            }
        });
    });

    $(document).on('click', '.currency-enable', function (e) {
        data_href = $(this).data('href');

        currency_tr = $(this).parent().parent().parent().parent().parent();

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    currency_tr.find('.currency-status').html('<span class="label label-success">{{ trans('general.enabled') }}</span>');
                }
            }
        });
    });

    $(document).on('change', '#code', function (e) {
        $.ajax({
            url: '{{ url("settings/currencies/config") }}',
            type: 'GET',
            dataType: 'JSON',
            data: 'code=' + $(this).val(),
            success: function(data) {
                $('#precision').val(data.precision);
                $('#symbol').val(data.symbol);
                $('#symbol_first').val(data.symbol_first);
                $('#decimal_mark').val(data.decimal_mark);
                $('#thousands_separator').val(data.thousands_separator);

                // This event Select2 Stylesheet
                $('#symbol_first').trigger('change');
            }
        });
    });

    function confirmCurrency(tr_id, title, message, button_cancel, button_delete) {
        $('#confirm-modal').remove();

        var html  = '';

        html += '<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">';
        html += '  <div class="modal-dialog">';
        html += '      <div class="modal-content">';
        html += '          <div class="modal-header">';
        html += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        html += '              <h4 class="modal-title" id="confirmModalLabel">' + title + '</h4>';
        html += '          </div>';
        html += '          <div class="modal-body">';
        html += '              <p>' + message + '</p>';
        html += '              <p></p>';
        html += '          </div>';
        html += '          <div class="modal-footer">';
        html += '              <div class="pull-left">';
        html += '                  <button type="button" class="btn btn-danger" onclick="deleteCurrency(\'' + tr_id + '\');">' + button_delete + '</button>';
        html += '                  <button type="button" class="btn btn-default" data-dismiss="modal">' + button_cancel + '</button>';
        html += '              </div>';
        html += '          </div>';
        html += '      </div>';
        html += '  </div>';
        html += '</div>';

        $('body').append(html);

        $('#confirm-modal').modal('show');
    }

    function deleteCurrency(tr_id) {
        data_href = $(tr_id).data('href');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $(tr_id).remove();
                }
            }
        });
    }
</script>
@endpush
