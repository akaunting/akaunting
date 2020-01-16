@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.reports', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'id' => 'report',
            'route' => 'reports.store',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::selectGroup('class', trans_choice('general.types', 1), 'bars', $classes, null, ['required' => 'required', 'change' => 'onChangeClass']) }}

                    {{ Form::textareaGroup('description', trans('general.description'), null, null, ['rows' => '3', 'required' => 'required']) }}

                    <component v-bind:is="report_fields"></component>
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
    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush
