@extends('layouts.admin')

@section('title', trans_choice('general.categories', 2))

@permission('create-settings-categories')
@section('new_button')
<span class="new-button"><a href="{{ url('settings/categories/create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
@endsection
@endpermission

@section('content')
<!-- Default box -->
<div class="box box-success">
    <div class="box-header with-border">
        {!! Form::open(['url' => 'settings/categories', 'role' => 'form', 'method' => 'GET']) !!}
        <div id="items" class="pull-left box-filter">
            <span class="title-filter hidden-xs">{{ trans('general.search') }}:</span>
            {!! Form::text('search', request('search'), ['class' => 'form-control input-filter input-sm', 'placeholder' => trans('general.search_placeholder')]) !!}
            {!! Form::select('types[]', $types, request('types'), ['id' => 'filter-types', 'class' => 'form-control input-filter input-lg', 'multiple' => 'multiple']) !!}
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
            <table class="table table-striped table-hover" id="tbl-categories">
                <thead>
                    <tr>
                        <th class="col-md-5">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-3">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-2 hidden-xs">{{ trans('general.color') }}</th>
                        <th class="col-md-1 hidden-xs">@sortablelink('enabled', trans_choice('general.statuses', 1))</th>
                        <th class="col-md-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($categories as $item)
                    <tr>
                        <td>
                        @if (($item->type == 'income') && $auth_user->can('read-reports-income-summary'))
                        <a href="{{ url('reports/income-summary?categories[]=' . $item->id) }}">{{ $item->name }}</a>
                        @elseif (($item->type == 'expense') && $auth_user->can('read-reports-expense-summary'))
                        <a href="{{ url('reports/expense-summary?categories[]=' . $item->id) }}">{{ $item->name }}</a>
                        @elseif (($item->type == 'item') && $auth_user->can('read-common-items'))
                        <a href="{{ url('common/items?categories[]=' . $item->id) }}">{{ $item->name }}</a>
                        @else
                        <a href="{{ url('settings/categories/' . $item->id . '/edit') }}">{{ $item->name }}</a>
                        @endif
                        </td>
                        <td>{{ $types[$item->type] }}</td>
                        <td class="hidden-xs"><i class="fa fa-2x fa-circle" style="color:{{ $item->color }};"></i></td>
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
                                    <li><a href="{{ url('settings/categories/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a></li>
                                    @if ($item->enabled)
                                    <li><a href="{{ route('categories.disable', $item->id) }}">{{ trans('general.disable') }}</a></li>
                                    @else
                                    <li><a href="{{ route('categories.enable', $item->id) }}">{{ trans('general.enable') }}</a></li>
                                    @endif
                                    @if ($item->id != $transfer_id)
                                    @permission('delete-settings-categories')
                                    <li class="divider"></li>
                                    <li>{!! Form::deleteLink($item, 'settings/categories') !!}</li>
                                    @endpermission
                                    @endif
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
        @include('partials.admin.pagination', ['items' => $categories, 'type' => 'categories'])
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $("#filter-types").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]) }}"
        });
    });
</script>
@endpush
