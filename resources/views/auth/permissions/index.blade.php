@extends('layouts.admin')

@section('title', trans_choice('general.permissions', 2))

@permission('create-auth-permissions')
    @section('new_button')
        <a href="{{ route('permissions.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endsection
@endpermission

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'permissions.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <akaunting-search
                        :placeholder="'{{ trans('general.search_placeholder') }}'"
                        :options="{{ json_encode([]) }}"
                    ></akaunting-search>
                </div>

                {{ Form::bulkActionRowGroup('general.permissions', $bulk_actions, ['group' => 'auth', 'type' => 'permissions']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">@sortablelink('display_name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-3 long-texts">@sortablelink('name', trans('general.code'))</th>
                        <th class="col-md-2 col-lg-3 d-none d-md-block long-texts">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($permissions as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4 long-texts"><a class="col-aka" href="{{ route('permissions.edit', $item->id) }}">{{ $item->display_name }}</a></td>
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-3 long-texts">{{ $item->name }}</td>
                            <td class="col-md-2 col-lg-3 d-none d-md-block long-texts">{{ $item->description }}</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('permissions.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-auth-permissions')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'permissions.destroy') !!}
                                        @endpermission
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
                @include('partials.admin.pagination', ['items' => $permissions])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/permissions.js?v=' . version('short')) }}"></script>
@endpush
