@extends('layouts.admin')

@section('title', trans_choice('general.roles', 2))

@can('create-auth-roles')
    @section('new_button')
        <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endsection
@endcan

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'roles.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Auth\Role" />
                </div>

                {{ Form::bulkActionRowGroup('general.roles', $bulk_actions, ['group' => 'auth', 'type' => 'roles']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-3 col-md-2 col-lg-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-4 col-lg-4">@sortablelink('display_name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-3 col-md-4 col-lg-3">@sortablelink('name', trans('general.code'))</th>
                        <th class="col-lg-3 d-none d-lg-block">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($roles as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-3 col-md-2 col-lg-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-4 col-lg-4"><a class="col-aka" href="{{ route('roles.edit', $item->id) }}">{{ $item->display_name }}</a></td>
                            <td class="col-xs-4 col-sm-3 col-md-4 col-lg-3">{{ $item->name }}</td>
                            <td class="col-lg-3 d-none d-lg-block">{{ $item->description }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('roles.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @can('delete-auth-roles')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'roles.destroy') !!}
                                        @endcan
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
                @include('partials.admin.pagination', ['items' => $roles])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/roles.js?v=' . version('short')) }}"></script>
@endpush
