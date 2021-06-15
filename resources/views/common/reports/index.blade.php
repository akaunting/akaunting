@extends('layouts.admin')

@section('title', trans_choice('general.reports', 2))

@section('new_button')
    @can('create-common-reports')
        <a href="{{ route('reports.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
    @endcan
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
                        @canany(['create-common-reports', 'update-common-reports', 'delete-common-reports'])
                            <a class="btn btn-sm items-align-center py-2 mr-0 card-action-button shadow-none--hover" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v text-primary"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @can('update-common-reports')
                                    <a class="dropdown-item" href="{{ route('reports.edit', $report->id) }}">{{ trans('general.edit') }}</a>
                                @endcan

                                @can('create-common-reports')
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('reports.duplicate', $report->id) }}">{{ trans('general.duplicate') }}</a>
                                @endcan

                                @can('delete-common-reports')
                                    <div class="dropdown-divider"></div>
                                    {!! Form::deleteLink($report, 'reports.destroy') !!}
                                @endcan
                            </div>
                        @endcanany

                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('reports.show', $report->id) }}">
                                        <h5 class="card-title text-uppercase text-muted mb-0">{{ $report->name }}</h5>
                                    </a>

                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('reports.show', $report->id) }}">
                                            <h2 class="font-weight-bold mb-0" v-if="reports_total[{{ $report->id }}]" v-html="reports_total[{{ $report->id }}]"></h2>
                                            <h2 class="font-weight-bold mb-0" v-else>{{ $totals[$report->id] }}</h2>
                                        </a>
    
                                        <button type="button" @click="onRefreshTotal('{{ $report->id }}')" class="btn btn-otline-primary btn-sm ml-2">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </div>
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
    <script type="text/javascript">
        var reports_total = {!! json_encode($totals) !!};
    </script>

    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush
