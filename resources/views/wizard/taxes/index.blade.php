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
                    <a href="{{ url('wizard/currencies') }}" type="button" class="btn btn-default btn-circle">2</a>
                    <p><small>{{ trans_choice('general.currencies', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-3" type="button" class="btn btn-success btn-circle">3</a>
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
        <h3 class="box-title">{{ trans_choice('general.taxes', 2) }}</h3>
        <span class="new-button"><a href="javascript:void(0);" data-href="{{ url('wizard/taxes/create') }}" class="btn btn-success btn-sm tax-create"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-taxes">
                <thead>
                <tr>
                    <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                    <th class="col-md-4">@sortablelink('rate', trans('taxes.rate_percent'))</th>
                    <th class="col-md-2 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                    <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($taxes as $item)
                    <tr id="tax-{{ $item->id }}" data-href="{{ url('wizard/taxes/' . $item->id . '/delete') }}">
                        <td class="tax-name"><a href="javascript:void(0);" data-id="{{ $item->id }}" data-href="{{ url('wizard/taxes/' . $item->id . '/edit') }}" class="tax-edit">{{ $item->name }}</a></td>
                        <td class="tax-rate">{{ $item->rate }}</td>
                        <td class="tax-status hidden-xs">
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td class="tax-action text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="javascript:void(0);" data-id="{{ $item->id }}" data-href="{{ url('wizard/taxes/' . $item->id . '/edit') }}" class="tax-edit">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                        <li><a href="javascript:void(0);" data-href="{{ url('wizard/taxes/' . $item->id . '/disable') }}" class="tax-disable">{{ trans('general.disable') }}</a></li>
                                    @else
                                        <li><a href="javascript:void(0);" data-href="{{ url('wizard/taxes/' . $item->id . '/enable') }}" class="tax-enable">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-settings-taxes')
                                    <li class="divider"></li>
                                    <li>
                                        {!! Form::button(trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'delete-link',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmTax("' . '#tax-' . $item->id . '", "' . trans_choice('general.taxes', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->name . '</strong>', 'type' => mb_strtolower(trans_choice('general.taxes', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
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
                <a href="{{ url('wizard/finish') }}" id="wizard-skip" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
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

    $(document).on('click', '.tax-create', function (e) {
        $('#tax-create').remove();
        $('#tax-edit').remove();

        data_href = $(this).data('href');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $('#tbl-taxes tbody').append(json['html']);

                    $("#code").select2({
                        placeholder: "{{ trans('general.form.select.field', ['field' => trans('taxes.code')]) }}"
                    });

                    $('.tax-enabled-radio-group #enabled_1').trigger('click');

                    $('#name').focus();
                }
            }
        });
    });

    $(document).on('click', '.tax-submit', function (e) {
        $(this).html('<span class="fa fa-spinner fa-pulse"></span>');
        $('.help-block').remove();

        data_href = $(this).data('href');

        $.ajax({
            url: '{{ url("wizard/taxes") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $('#tbl-taxes input[type=\'number\'], #tbl-taxes input[type=\'text\'], #tbl-taxes input[type=\'radio\'], #tbl-taxes input[type=\'hidden\'], #tbl-taxes textarea, #tbl-taxes select').serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(json) {
                $('.tax-submit').html('<span class="fa fa-save"></span>');

                if (json['success']) {
                    tax = json['data'];
                    $('#tax-create').remove();
                    $('#tax-edit').remove();

                    html  = '<tr id="tax-' + tax.id + '" data-href="wizard/taxes/' + tax.id + '/delete">';
                    html += '   <td class="tax-name">';
                    html += '       <a href="javascript:void(0);" data-id="' + tax.id + '" data-href="wizard/taxes/' + tax.id + '/edit" class="tax-edit">' + tax.name + '</a>';
                    html += '   </td>';
                    html += '   <td class="tax-rate">' + tax.rate + '</td>';
                    html += '   <td class="tax-status hidden-xs">';
                    html += '       <span class="label label-success">Enabled</span>';
                    html += '   </td>';
                    html += '   <td class="tax-action text-center">';
                    html += '       <div class="btn-group">';
                    html += '           <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">';
                    html += '               <i class="fa fa-ellipsis-h"></i>';
                    html += '           </button>';
                    html += '           <ul class="dropdown-menu dropdown-menu-right">';
                    html += '               <li><a href="javascript:void(0);" data-id="' + tax.id + '" data-href="wizard/taxes/' + tax.id + '/edit" class="tax-edit">{{ trans('general.edit') }}</a></li>';
                    html += '               <li><a href="javascript:void(0);" data-href="wizard/taxes/' + tax.id + '/disable" class="tax-disable">{{ trans('general.disable') }}</a></li>';
                    html += '               <li class="divider"></li>';
                    html += '               <li>';
                    html += '                   <button type="button" class="delete-link" title="{{ trans('general.delete') }}" onclick="confirmCurrency("#tax-' + tax.id + '", "{{ trans_choice('general.taxes', 2) }}", "{{ trans('general.delete_confirm', ['name' => !empty($item) ? '<strong>' . $item->name . '</strong>' : '', 'type' => mb_strtolower(trans_choice('general.taxes', 1))]) }}", "{{ trans('general.cancel') }}", "{{ trans('general.delete') }}")">{{ trans('general.delete') }}</button>';
                    html += '               </li>';
                    html += '           </ul>';
                    html += '       </div>';
                    html += '   </td>';
                    html += '</tr>';

                    $('#tbl-taxes tbody').append(html);
                }
            },
            error: function(data){
                $('.tax-submit').html('<span class="fa fa-save"></span>');

                var errors = data.responseJSON;

                if (typeof errors !== 'undefined') {
                    if (errors.name) {
                        $('#tbl-taxes #name').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.name + '</p>');
                    }

                    if (errors.code) {
                        $('#tbl-taxes #code').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.code + '</p>');
                    }

                    if (errors.rate) {
                        $('#tbl-taxes #rate').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.rate + '</p>');
                    }
                }
            }
        });
    });

    $(document).on('click', '.tax-edit', function (e) {
        $('#tax-create').remove();
        $('#tax-edit').remove();

        data_href = $(this).data('href');
        data_id = $(this).data('id');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $('#tax-' + data_id).after(json['html']);
                    $('#enabled_1').trigger('click');
                    $('#name').focus();

                    $("#code").select2({
                        placeholder: "{{ trans('general.form.select.field', ['field' => trans('taxes.code')]) }}"
                    });

                    $('.tax-enabled-radio-group #enabled_1').trigger();
                }
            }
        });
    });

    $(document).on('click', '.tax-updated', function (e) {
        $(this).html('<span class="fa fa-spinner fa-pulse"></span>');
        $('.help-block').remove();

        data_href = $(this).data('href');

        $.ajax({
            url: data_href,
            type: 'PATCH',
            dataType: 'JSON',
            data: $('#tbl-taxes input[type=\'number\'], #tbl-taxes input[type=\'text\'], #tbl-taxes input[type=\'radio\'], #tbl-taxes input[type=\'hidden\'], #tbl-taxes textarea, #tbl-taxes select').serialize(),
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(json) {
                $('.tax-updated').html('<span class="fa fa-save"></span>');

                if (json['success']) {
                    $('#tax-' + data_id + ' .tax-name a').text($('#tax-edit #name').val());
                    $('#tax-' + data_id + ' .tax-rate').text($('#tax-edit #rate').val());

                    if ($('#tax-edit #enabled').val()) {
                        $('#tax-' + data_id + ' .tax-status').html('<span class="label label-success">{{ trans('general.enabled') }}</span>');
                    } else {
                        $('#tax-' + data_id + ' .tax-status').html('<span class="label label-danger">{{ trans('general.disabled') }}</span>');
                    }

                    $('#tax-create').remove();
                    $('#tax-edit').remove();
                }
            },
            error: function(data){
                $('.tax-updated').html('<span class="fa fa-save"></span>');

                var errors = data.responseJSON;

                if (typeof errors !== 'undefined') {
                    if (errors.name) {
                        $('#tbl-taxes #name').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.name + '</p>');
                    }

                    if (errors.code) {
                        $('#tbl-taxes #code').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.code + '</p>');
                    }

                    if (errors.rate) {
                        $('#tbl-taxes #rate').parent().after('<p class="help-block" style="color: #ca1313;">' + errors.rate + '</p>');
                    }
                }
            }
        });
    });

    $(document).on('click', '.tax-disable', function (e) {
        data_href = $(this).data('href');

        tax_tr = $(this).parent().parent().parent().parent().parent();

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    tax_tr.find('.tax-status').html('<span class="label label-danger">{{ trans('general.disabled') }}</span>');
                }
            }
        });
    });

    $(document).on('click', '.tax-enable', function (e) {
        data_href = $(this).data('href');

        tax_tr = $(this).parent().parent().parent().parent().parent();

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    tax_tr.find('.tax-status').html('<span class="label label-success">{{ trans('general.enabled') }}</span>');
                }
            }
        });
    });

    $(document).on('change', '#code', function (e) {
        $.ajax({
            url: '{{ url("settings/taxes/config") }}',
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
