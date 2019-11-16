@extends('layouts.admin')

@section('title', trans_choice('general.companies', 2))

@permission('create-common-companies')
    @section('new_button')
        <span><a href="{{ route('companies.create') }}" class="btn btn-success btn-sm btn-alone"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
    @endsection
@endpermission

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'url' => 'common/companies',
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

                {{ Form::bulkActionRowGroup('general.companies', $bulk_actions, 'common/companies') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm">@sortablelink('id', trans('general.id'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-3 o-y">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-2 col-lg-2 col-xl-2 hidden-md o-y">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-lg-2 col-xl-2 hidden-lg">@sortablelink('created_at', trans('general.created'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($companies as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionGroup($item->id, $item->name) }}</td>
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm"><a class="col-aka">{{ $item->id }}</a></td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-3 o-y"><a class="text-success" href="{{ url('common/companies/' . $item->id . '/edit') }}">{{ $item->name }}</a></td>
                            <td class="col-md-2 col-lg-2 col-xl-2 hidden-md o-y">{{ $item->email }}</td>
                            <td class="col-lg-2 col-xl-2 hidden-lg border-0">@date($item->created_at)</td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">
                                @if (user()->can('update-common-companies'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                    @else
                                        <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                    @endif
                                @endif
                            </td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                <div class="dropdown">
                                    <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-h text-muted"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @if ($item->enabled)
                                            <a  class="dropdown-item" href="{{ route('companies.switch', $item->id) }}">{{ trans('general.switch') }}</a>
                                            <div class="dropdown-divider"></div>
                                        @endif
                                        <a class="dropdown-item" href="{{ url('common/companies/' . $item->id . '/edit') }}">{{ trans('general.edit') }}</a>
                                        @permission('delete-common-companies')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'common/companies', '', 'company_name') !!}
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
                @include('partials.admin.pagination', ['items' => $companies, 'type' => 'companies'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/companies.js?v=' . version('short')) }}"></script>
@endpush
