@extends('layouts.customer')

@section('title', trans('general.dashboard'))

@section('content')
    <div class="row">
        <!---Income, Expense and Profit Line Chart-->
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body" id="chart">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="customer-content">
                                <div class="pull-left">{{ trans('general.unpaid') }}</div>
                                <div class="pull-right">{{ $progress['total'] }} / {{ $progress['unpaid'] }}</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-red" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }}%">
                                        {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['unpaid'] : '0' }} %
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="customer-content">
                                <div class="pull-left">{{ trans('general.paid') }}</div>
                                <div class="pull-right">{{ $progress['total'] }} / {{ $progress['paid'] }}</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-green" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}%">
                                        {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['paid'] : '0' }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="customer-content">
                                <div class="pull-left">{{ trans('general.overdue') }}</div>
                                <div class="pull-right">{{ $progress['total'] }} / {{ $progress['overdue'] }}</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-yellow" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}%">
                                        {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['overdue'] : '0' }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="customer-content">
                                <div class="pull-left">{{ trans('general.partially_paid') }}</div>
                                <div class="pull-right">{{ $progress['total'] }} / {{ $progress['partially_paid'] }}</div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-light-blue" role="progressbar" aria-valuenow="{{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}%">
                                        {{ !empty($progress['total']) ? (100 / $progress['total']) * $progress['partially_paid'] : '0' }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {!! $chart->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
{!! Charts::assets() !!}
@endpush