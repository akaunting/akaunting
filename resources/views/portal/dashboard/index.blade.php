@extends('layouts.portal')

@section('title', trans('general.dashboard'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="progress-info">
                                <div class="progress-label-danger">
                                    <span>{{ trans('general.unpaid') }}</span>
                                </div>
                                <div class="progress-percentage">
                                    <span>{{ $progress['total'] }} / {{ $progress['unpaid'] }}</span>
                                </div>
                            </div>
                            <div class="progress h-25">
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}%">
                                    {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }} %
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="progress-info">
                                <div class="progress-label-success">
                                    <span>{{ trans('general.paid') }}</span>
                                </div>
                                <div class="progress-percentage">
                                    <span>{{ $progress['total'] }} / {{ $progress['paid'] }}</span>
                                </div>
                            </div>
                            <div class="progress h-25">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}%">
                                    {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}%
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="progress-info">
                                <div class="progress-label-warning">
                                    <span>{{ trans('general.overdue') }}</span>
                                </div>
                                <div class="progress-percentage">
                                    <span>{{ $progress['total'] }} / {{ $progress['overdue'] }}</span>
                                </div>
                            </div>
                            <div class="progress h-25">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}%">
                                    {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}%
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="progress-info">
                                <div class="progress-label-info">
                                    <span>{{ trans('general.partially_paid') }}</span>
                                </div>
                                <div class="progress-percentage">
                                    <span>{{ $progress['total'] }} / {{ $progress['partially_paid'] }}</span>
                                </div>
                            </div>
                            <div class="progress h-25">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}%">
                                    {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}%
                                </div>
                            </div>
                        </div>
                    </div>
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