@extends('layouts.admin')

@section('title', trans_choice('general.permissions', 2))

@permission('update-auth-permissions')
    @section('new_button')
        <span><a href="{{ route('permissions.create') }}" class="btn btn-success btn-sm btn-alone"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    @endsection
@endpermission

@section('content')
    <div class="card">
         <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
             {!! Form::open([
                 'url' => 'auth/permissions',
                 'role' => 'form',
                 'method' => 'GET',
                 'class' => 'mb-0'
             ]) !!}
                 <div class="row"  v-if="!bulk_action.show">
                     <div class="col-12 card-header-search">
                        <span class="table-text hidden-lg">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                     </div>
                 </div>

                {{ Form::bulkActionRowGroup('general.permissions', $bulk_actions, 'auth/permissions') }}
             {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-1 col-lg-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">@sortablelink('display_name', trans('general.name'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-3 o-y">@sortablelink('name', trans('general.code'))</th>
                        <th class="col-md-2 col-lg-3 o-y hidden-md">@sortablelink('description', trans('general.description'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($permissions as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-1 col-lg-1 hidden-sm border-0">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-xs-4 col-sm-4 col-md-4 col-lg-4 border-0"><a class="text-success col-aka" href="{{ route('permissions.edit', $item->id) }}">{{ $item->display_name }}</a></td>
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-3 o-y border-0">{{ $item->name }}</td>
                            <td class="col-md-2 col-lg-3 o-y hidden-md border-0">{{ $item->description }}</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 text-center border-0">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item" href="{{ route('permissions.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        <div class="dropdown-divider"></div>
                                        @permission('delete-auth-permissions')
                                            {!! Form::deleteLink($item, 'auth/permissions') !!}
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
                @include('partials.admin.pagination', ['items' => $permissions, 'type' => 'permissions'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/permissions.js?v=' . version('short')) }}"></script>
@endpush
