@extends('layouts.admin')

@section('title', trans_choice('general.reports', 2))

@section('new_button')
    @permission('create-common-reports')
        <a href="{{ route('reports.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endpermission
    <a href="{{ route('reports.clear') }}" class="btn btn-warning btn-sm header-button-top"><span class="fa fa-history"></span> &nbsp;{{ trans('general.clear_cache') }}</a>
@endsection

@section('content')
    <div class="row mb-4">
        @foreach($categories as $name => $reports)
            <div class="col-md-12">
                <h3>{{ $name }}</h3>
            </div>

            @foreach($reports as $report)
                <div class="col-md-4">
                    <div class="card card-stats">
                        <span>
                            <div class="dropdown card-action-button">
                                <a class="btn btn-sm items-align-center py-2 mr-0 shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-primary"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="{{ route('reports.edit', $report->id) }}">{{ trans('general.edit') }}</a>
                                    @permission('create-common-reports')
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('reports.duplicate', $report->id) }}">{{ trans('general.duplicate') }}</a>
                                    @endpermission
                                    @permission('delete-common-reports')
                                        <div class="dropdown-divider"></div>
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
                                        <h2 class="font-weight-bold mb-0">{{ $totals[$report->id] }}</h2>
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('reports.show', $report->id) }}">
                                        <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                            <i class="{{ $icons[$report->id] }}"></i>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a class="text-default" href="{{ route('reports.show', $report->id) }}">
                                    <span class="pre">{{ $report->description }}</span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

        @endforeach
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush

