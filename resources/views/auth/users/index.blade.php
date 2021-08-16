@extends('layouts.admin')

@section('title', trans_choice('general.users', 2))

@can('create-auth-users')
    @section('new_button')
        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endsection
@endcan

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">

                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Auth\User" />
                </div>

                {{ Form::bulkActionRowGroup('general.users', $bulk_actions, ['group' => 'auth', 'type' => 'users']) }}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-3">@sortablelink('name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-sm-2 col-md-2 col-lg-3 d-none d-sm-block long-texts">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-md-2 col-lg-2 d-none d-md-block">{{ trans_choice('general.roles', 2) }}</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 d-none d-sm-block">
                                @if (user()->id != $item->id)
                                    {{ Form::bulkActionGroup($item->id, $item->name) }}
                                @else
                                    {{ Form::bulkActionGroup($item->id, $item->name, ['disabled' => 'true']) }}
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-3">
                                <a class="col-aka" href="{{ route('users.edit', $item->id) }}">
                                    @if (setting('default.use_gravatar', '0') == '1')
                                        <img src="{{ $item->picture }}" alt="{{ $item->name }}" class="rounded-circle user-img p-1 mr-3 d-none d-md-inline" title="{{ $item->name }}">
                                    @elseif (is_object($item->picture))
                                        <img src="{{ Storage::url($item->picture->id) }}" class="rounded-circle user-img p-1 mr-3 d-none d-md-inline" alt="{{ $item->name }}" title="{{ $item->name }}">
                                    @else
                                        <img src="{{ asset('public/img/user.svg') }}" class="user-img p-1 mr-3 d-none d-md-inline" alt="{{ $item->name }}"/>
                                    @endif
                                    {{ $item->name }}
                                </a>
                            </td>
                            <td class="col-sm-2 col-md-2 col-lg-3 d-none d-sm-block long-texts">{{ $item->email }}</td>
                            <td class="col-md-2 col-lg-2 d-none d-md-block">
                                @foreach($item->roles as $role)
                                    <label class="badge badge-default">{{ $role->display_name }}</label>
                                @endforeach
                            </td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2">
                                @if ((user()->id != $item->id) && user()->can('update-auth-users'))
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
                                        <a class="dropdown-item" href="{{ route('users.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @if (user()->id != $item->id)
                                            @can('delete-auth-users')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'users.destroy') !!}
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
                @include('partials.admin.pagination', ['items' => $users])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/users.js?v=' . version('short')) }}"></script>
@endpush
