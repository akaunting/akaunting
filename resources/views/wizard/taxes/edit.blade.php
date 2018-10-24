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
                    <tr>
                        <td><a href="{{ url('settings/taxes/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                        <td>{{ $item->rate }}</td>
                        <td class="hidden-xs">
                            @if ($item->enabled)
                                <span class="label label-success">{{ trans('general.enabled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('general.disabled') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ url('settings/taxes/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                        <li><a href="{{ route('taxes.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                    @else
                                        <li><a href="{{ route('taxes.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @permission('delete-settings-taxes')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'settings/taxes', 'tax_rates') !!}</li>
                                    @endpermission
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-right">
                        <span class="new-button"><a href="{{ url('settings/taxes/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            <div class="form-group no-margin">
                {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'submit', 'class' => 'btn btn-success  button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                <a href="{{ url('wizard/skip') }}" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
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

    $(document).ready(function() {

    });
</script>
@endpush
