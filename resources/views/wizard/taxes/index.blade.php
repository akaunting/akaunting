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
    <div class="box-header with-border">
        <h3 class="box-title">{{ trans_choice('general.taxes', 1) }}</h3>
        <span class="new-button"><a href="{{ url('settings/taxes/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-taxes">
                <thead>
                <tr>
                    <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                    <th class="col-md-5">@sortablelink('rate', trans('taxes.rate_percent'))</th>
                    <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                    <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($taxes as $item)
                    <tr id="tax-{{ $item->id }}" data-href="{{ url('wizard/taxes/' . $item->id . '/delete') }}">
                        <td class="tax-name"><a href="javascript:void(0);" data-href="{{ url('wizard/taxes/' . $item->id . '/edit') }}" class="tax-edit">{{ $item->name }}</a></td>
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
                                    <li><a href="javascript:void(0);" data-href="{{ url('wizard/taxes/' . $item->id . '/edit') }}" class="tax-edit">{{ trans('general.edit') }}</a></li>
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
                <a href="{{ url('wizard/finish') }}" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
            </div>
        </div>
    </div>
    <!-- /.box-footer -->

</div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).on('click', '.tax-edit', function (e) {
        data_href = $(this).data('href');

        $.ajax({
            url: data_href,
            type: 'GET',
            dataType: 'JSON',
            success: function(json) {
                if (json['success']) {
                    $('body').append(json['html']);
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

    function confirmTax(tr_id, title, message, button_cancel, button_delete) {
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
        html += '                  <button type="button" class="btn btn-danger" onclick="deleteTax(\'' + tr_id + '\');">' + button_delete + '</button>';
        html += '                  <button type="button" class="btn btn-default" data-dismiss="modal">' + button_cancel + '</button>';
        html += '              </div>';
        html += '          </div>';
        html += '      </div>';
        html += '  </div>';
        html += '</div>';

        $('body').append(html);

        $('#confirm-modal').modal('show');
    }

    function deleteTax(tr_id) {
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
