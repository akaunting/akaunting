@extends('layouts.admin')

@section('title', trans_choice('general.companies', 2))

@can('create-common-companies')
    @section('new_button')
        <a href="{{ route('companies.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endsection
@endcan

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
            {!! Form::open([
                'method' => 'GET',
                'route' => 'companies.index',
                'role' => 'form',
                'class' => 'mb-0'
            ]) !!}
                <div class="align-items-center" v-if="!bulk_action.show">
                    <x-search-string model="App\Models\Common\Company" />
                </div>

                {{ Form::bulkActionRowGroup('general.companies', $bulk_actions, ['group' => 'common', 'type' => 'companies']) }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">@sortablelink('id', trans('general.id'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-3 long-texts">@sortablelink('name', trans('general.name'))</th>
                        <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block long-texts">@sortablelink('email', trans('general.email'))</th>
                        <th class="col-lg-2 col-xl-2 d-none d-lg-block">@sortablelink('created_at', trans('general.created'))</th>
                        <th class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">@sortablelink('enabled', trans('general.enabled'))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($companies as $item)
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">
                                @if ((company_id() != $item->id))
                                    {{ Form::bulkActionGroup($item->id, $item->name) }}
                                @else
                                    {{ Form::bulkActionGroup($item->id, $item->name, ['disabled' => 'true']) }}
                                @endif
                            </td>
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block"><a class="col-aka">{{ $item->id }}</a></td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-3 col-xl-3 long-texts"><a href="{{ route('companies.edit', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block long-texts">{{ $item->email }}</td>
                            <td class="col-lg-2 col-xl-2 d-none d-lg-block">@date($item->created_at)</td>
                            <td class="col-xs-4 col-sm-3 col-md-2 col-lg-2 col-xl-2">
                                @if ((company_id() != $item->id) && user()->can('update-common-companies'))
                                    {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                @else
                                    @if ($item->enabled)
                                        <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                    @else
                                        <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
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
                                        <a class="dropdown-item" href="{{ route('companies.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                        @can('delete-common-companies')
                                            <div class="dropdown-divider"></div>
                                            {!! Form::deleteLink($item, 'companies.destroy') !!}
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
                @include('partials.admin.pagination', ['items' => $companies])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/companies.js?v=' . version('short')) }}"></script>
@endpush
