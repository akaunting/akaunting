@extends('layouts.admin')

@section('title', trans_choice('general.categories', 2))

@section('new_button')
    @can('create-settings-categories')
        <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        <a href="{{ route('import.create', ['settings', 'categories']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endcan
    <a href="{{ route('categories.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'categories.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Setting\Category" />
                </div>

                {{ Form::bulkActionRowGroup('general.categories', $bulk_actions, ['group' => 'settings', 'type' => 'categories']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-4">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-sm-2 col-md-2 col-lg-2 d-none d-sm-block">@sortablelink('type', trans_choice('general.types', 1))</th>
                        <th class="col-md-2  col-lg-2 d-none d-md-block">{{ trans('general.color') }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($categories as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 d-none d-sm-block">
                                {{ Form::bulkActionGroup($item->id, $item->name) }}
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-4"><a class="col-aka" href="{{ route('categories.edit',  $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-sm-2 col-md-2 col-lg-2 d-none d-sm-block">{{ $types[$item->type] ?? trans('general.na') }}</td>
                            <td class="col-md-2  col-lg-2 d-none d-md-block"><i class="fa fa-2x fa-circle" style="color:{{ $item->color }};"></i></td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
                                @if (user()->can('update-settings-categories'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                    @else
                                        <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('categories.edit',  $item->id) }}">{{ trans('general.edit') }}</a>
                                        @if ($item->id != $transfer_id)
                                            @can('delete-settings-categories')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'categories.destroy') !!}
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $categories])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/categories.js?v=' . version('short')) }}"></script>
@endpush
