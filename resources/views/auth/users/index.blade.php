@extends('layouts.admin')

@section('title', trans_choice('general.users', 2))

@permission('create-auth-users')
    @section('new_button')
        <span><a href="{{ route('users.create') }}" class="btn btn-success btn-sm btn-alone"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    @endsection
@endpermission

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'url' => 'auth/users',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
                <div class="row" v-if="!bulk_action.show">
                    <div class="col-12 card-header-search">
                        <span class="table-text hidden-lg">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                     </div>
                </div>

                {{ Form::bulkActionRowGroup('general.users', $bulk_actions, 'auth/users') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-sm-2 col-md-2 col-lg-3 hidden-sm o-y">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-2 col-lg-2 hidden-md">{{ trans_choice('general.roles', 2) }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-3 border-0">
                                <a class="text-success col-aka" href="{{ route('users.edit', $item->id) }}">
                                    @if (setting('default.use_gravatar', '0') == '1')
                                        <img src="{{ $item->picture }}" alt="{{ $item->name }}" title="{{ $item->name }}">
                                    @else
                                        @if ($item->picture)
                                            <img src="{{ Storage::url($item->picture->id) }}" alt="{{ $item->name }}" title="{{ $item->name }}">
                                        @endif
                                    @endif
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="col-sm-2 hidden-sm col-md-2 col-lg-3 o-y border-0">{{ $item->email }}</td>
                            <td class="col-md-2 col-lg-2 hidden-md border-0">
                                @foreach($item->roles as $role)
                                    <label class="label label-default">{{ $role->display_name }}</label>
                                @endforeach
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 border-0">
                                @if (user()->can('update-auth-users'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                    @else
                                        <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2  col-lg-1 text-center border-0">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('users.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-auth-users')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'auth/users') !!}
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
                @include('partials.admin.pagination', ['items' => $users, 'type' => 'users'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/users.js?v=' . version('short')) }}"></script>
@endpush
