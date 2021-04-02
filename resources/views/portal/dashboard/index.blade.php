@extends('layouts.portal')

@section('title', trans_choice('general.dashboards', 1))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-gradient-success card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.paid') }}</h5>
                            <span class="font-weight-bold text-white mb-0">{{ $totals['paid'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-success rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ $progress['paid'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['paid'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-warning card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.unpaid') }}</h5>
                            <span class="font-weight-bold text-white mb-0">{{ $totals['unpaid'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-warning rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ $progress['unpaid'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['unpaid'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-gradient-danger card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-uppercase text-white mb-0">{{ trans('general.overdue') }}</h5>
                            <span class="font-weight-bold text-white mb-0">{{ $totals['overdue'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-white text-danger rounded-circle shadow">
                                <i class="fa fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="progress progress-xs mb-0">
                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="{{ $progress['overdue'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $progress['overdue'] }}%"></div>
                    </div>
                </div>
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
