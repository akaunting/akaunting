@extends('layouts.portal')

@section('title', trans_choice('general.dashboards', 1))

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-gradient-danger card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.unpaid') }}</h5>
                            <span class="font-weight-bold text-white mb-0">@money($progress['unpaid'], setting('default.currency'), true)</span>
                        </div>

                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-danger rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <el-tooltip
                content="{{ $progress['total'] }} / {{ $progress['unpaid'] }}"
                effect="dark"
                :open-delay="100"
                placement="top">
                    <div class="card-footer">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}%"></div>
                        </div>
                    </div>
                </el-tooltip>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-gradient-success card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.paid') }}</h5>
                            <span class="font-weight-bold text-white mb-0">@money($progress['paid'], setting('default.currency'), true)</span>
                        </div>

                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <el-tooltip
                content="{{ $progress['total'] }} / {{ $progress['paid'] }}"
                effect="dark"
                :open-delay="100"
                placement="top">
                    <div class="card-footer">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}%"></div>
                        </div>
                    </div>
                </el-tooltip>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-gradient-warning card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.overdue') }}</h5>
                            <span class="font-weight-bold text-white mb-0">@money($progress['overdue'], setting('default.currency'), true)</span>
                        </div>

                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-warning rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <el-tooltip
                content="{{ $progress['total'] }} / {{ $progress['overdue'] }}"
                effect="dark"
                :open-delay="100"
                placement="top">
                    <div class="card-footer">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}%"></div>
                        </div>
                    </div>
                </el-tooltip>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-gradient-info card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.partially_paid') }}</h5>
                            <span class="font-weight-bold text-white mb-0">@money($progress['partially_paid'], setting('default.currency'), true)</span>
                        </div>

                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-info rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <el-tooltip
                content="{{ $progress['total'] }} / {{ $progress['partially_paid'] }}"
                effect="dark"
                :open-delay="100"
                placement="top">
                    <div class="card-footer">
                        <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}%"></div>
                        </div>
                    </div>
                </el-tooltip>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('widgets.cash_flow') }}</h4>
                </div>
                <div class="card-body" id="chart">
                    {!! $chart->container() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('body_js')
    {!! $chart->script() !!}
@endpush

@push('scripts_start')
    <script src="{{ asset('public/js/portal/dashboard.js?v=' . version('short')) }}"></script>
@endpush
