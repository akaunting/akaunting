@extends('layouts.admin')

@section('title', trans_choice('general.reconciliations', 2))

@permission('create-banking-reconciliations')
@section('new_button')
<span class="new-button"><a href="{{ route('reconciliations.create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'banking/reconciliations', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left box-filter">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::select('accounts[]', $accounts, request('accounts'), ['id' => 'filter-accounts', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-default btn-filter']) !!}
        </div>
        <div class="pull-right">
            <span class="title-filter hidden-xs">{{ trans('general.show') }}:</span>
            {!! Form::select('limit', $limits, request('limit', setting('general.list_limit', '25')), ['class' => 'form-control input-filter input-sm', 'onchange' => 'this.form.submit()']) !!}
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="table table-responsive">
            <table class="table table-striped table-hover" id="tbl-reconciliations">
                <thead>
                    <tr>
                        <th class="col-md-2">@sortablelink('created_at', trans('general.created_date'))</th>
                        <th class="col-md-3">@sortablelink('account_id', trans_choice('general.accounts', 1))</th>
                        <th class="col-md-3 hidden-xs">{{ trans('general.period') }}</th>
                        <th class="col-md-2 text-right amount-space">@sortablelink('closing_balance', trans('reconciliations.closing_balance'))</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($reconciliations as $item)
                    <tr>
                        <td><a href="{{ route('reconciliations.edit', $item->id) }}">{{ Date::parse($item->created_at)->format($date_format) }}</a></td>
                        <td>{{ $item->account->name }}</td>
                        <td class="hidden-xs">{{ Date::parse($item->started_at)->format($date_format) }} - {{ Date::parse($item->ended_at)->format($date_format) }}</td>
                        <td class="text-right amount-space">@money($item->closing_balance, $item->account->currency_code, true)</td>
                        <td class="hidden-xs">
                            @if ($item->reconciled)
                                <span class="label label-success">{{ trans('reconciliations.reconciled') }}</span>
                            @else
                                <span class="label label-danger">{{ trans('reconciliations.unreconciled') }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" data-toggle-position="left" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{{ route('reconciliations.edit', $item->id) }}">{{ trans('general.edit') }}</a></li>
                                    @permission('delete-banking-reconciliations')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'banking/reconciliations') !!}</li>
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
        @include('partials.admin.pagination', ['items' => $reconciliations, 'type' => 'reconciliations'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $("#filter-accounts").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
        });
    });
</script>
@endpush
