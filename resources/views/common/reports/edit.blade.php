@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.reports', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($report, [
            'id' => 'report',
            'method' => 'PATCH',
            'route' => ['reports.update', $report->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::selectGroup('class', trans_choice('general.types', 1), 'bars', $classes, $report->class, ['required' => 'required', 'change' => 'onChangeClass']) }}

                    {{ Form::textareaGroup('description', trans('general.description'), null, null, ['rows' => '3', 'required' => 'required']) }}

                    {{ Form::selectGroup('group', trans('general.group_by'), 'folder', $groups, $report->group) }}

                    {{ Form::selectGroup('period', trans('general.period'), 'calendar', $periods, $report->period) }}

                    {{ Form::selectGroup('basis', trans('general.basis'), 'file', $basises, $report->basis) }}

                    {{ Form::selectGroup('chart', trans_choice('general.charts', 1), 'chart-pie', $charts, $report->chart) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('common/reports') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var class = '';
    </script>

    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush
