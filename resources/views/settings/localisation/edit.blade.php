@extends('layouts.admin')

@section('title', trans_choice('general.localisations', 1))

@section('content')
    {!! Form::open([
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::dateGroup('financial_start', trans('settings.localisation.financial_start'), 'calendar', ['id' => 'financial_start', 'class' => 'form-control datepicker', 'show-date-format' => 'j F', 'date-format' => 'd-m', 'autocomplete' => 'off', 'hidden_year' => true], setting('localisation.financial_start')) }}

                {{ Form::selectGroup('financial_denote', trans('settings.localisation.financial_denote.title'), 'calendar', $financial_denote_options, setting('localisation.financial_denote'), []) }}

                {{ Form::selectGroupGroup('timezone', trans('settings.localisation.timezone'), 'globe', $timezones, setting('localisation.timezone'), []) }}

                {{ Form::selectGroup('date_format', trans('settings.localisation.date.format'), 'calendar', $date_formats, setting('localisation.date_format'), ['autocomplete' => 'off']) }}

                {{ Form::selectGroup('date_separator', trans('settings.localisation.date.separator'), 'minus', $date_separators, setting('localisation.date_separator'), []) }}

                {{ Form::selectGroup('percent_position', trans('settings.localisation.percent.title'), 'percent', $percent_positions, setting('localisation.percent_position'), []) }}

                {{ Form::selectGroup('discount_location', trans('settings.localisation.discount_location.name'), 'percent', $discount_locations, setting('localisation.discount_location'), []) }}
            </div>
        </div>

        @can('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endcan
    </div>

    {!! Form::hidden('_prefix', 'localisation') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
