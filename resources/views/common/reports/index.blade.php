@extends('layouts.admin')

@section('title', trans_choice('general.reports', 2))

@section('new_button')
    @permission('create-common-reports')
    <span class="new-button">
        <a href="{{ route('reports.create') }}" class="btn btn-success btn-sm"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    </span>
    @endpermission
@endsection

@section('content')
    <div class="row mb-4">

        <div class="col-md-12">
            <h3 id="stats">{{ trans('reports.income-expense') }}</h3>
        </div>

        @foreach($reports['income-expense'] as $report)
            <div class="col-md-4">
                <div class="card card-stats">
                    <span>
                        <div class="dropdown card-action-button">
                            <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-primary"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @permission('create-common-reports')
                                <a class="dropdown-item" href="{{ route('reports.edit', $report->id) }}">{{ trans('general.edit') }}</a>
                                <div class="dropdown-divider"></div>
                                @endpermission
                                @permission('delete-common-reports')
                                {!! Form::deleteLink($report, 'common/reports') !!}
                                @endpermission
                            </div>
                        </div>
                    </span>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('reports.show', $report->id) }}">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ $report->name }}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $classes[$report->id]->getTotal() }}</span>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('reports.show', $report->id) }}">
                                    <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                        <i class="{{ $classes[$report->id]->getIcon() }}"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="{{ route('reports.show', $report->id) }}">
                                <span class="text-nowrap">{{ $report->description }}</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-md-12">
            <h3 id="stats">{{ trans('general.accounting') }}</h3>
        </div>

        @foreach($reports['accounting'] as $report)
            <div class="col-md-4">
                <div class="card card-stats">
                    <span>
                        <div class="dropdown card-action-button">
                            <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-primary"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @permission('create-common-reports')
                                <a class="dropdown-item" href="{{ route('reports.edit', $report->id) }}">{{ trans('general.edit') }}</a>
                                <div class="dropdown-divider"></div>
                                @endpermission
                                @permission('delete-common-reports')
                                {!! Form::deleteLink($report, 'common/reports') !!}
                                @endpermission
                            </div>
                        </div>
                    </span>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('reports.show', $report->id) }}">
                                    <h5 class="card-title text-uppercase text-muted mb-0">{{ $report->name }}</h5>
                                    <span class="h2 font-weight-bold mb-0">{{ $classes[$report->id]->getTotal() }}</span>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('reports.show', $report->id) }}">
                                    <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                        <i class="{{ $classes[$report->id]->getIcon() }}"></i>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                            <a href="{{ route('reports.show', $report->id) }}">
                                <span class="text-nowrap">{{ $report->description }}</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush

