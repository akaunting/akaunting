@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
    <div class="card">
        {!! Form::model($company, [
            'method' => 'PATCH',
            'route' => ['wizard.companies.update'],
            'id' => 'company',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button mb-0',
            'novalidate' => true
        ]) !!}

            <div id="wizard-loading"></div>
            @include('partials.wizard.steps')

            <div class="card-body">
                <div id="wizard-loading"></div>
                <div class="row mb--4">
                    <div class="col-md-12 {!! (!setting('apps.api_key', null)) ?: 'hidden' !!}">
                        <div class="form-group {{ $errors->has('api_key') ? 'has-error' : ''}}"
                            :class="[{'has-error': form.errors.get('api_key') }]">
                            {!! Form::label('api-key', trans('modules.api_key'), ['class' => 'form-control-label']) !!}

                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-key"></i>
                                    </span>
                                </div>
                                {!! Form::text('api_key', setting('apps.api_key', null), array_merge([
                                    'class' => 'form-control',
                                    'data-name' => 'api_key',
                                    'data-value' => setting('apps.api_key', null),
                                    'placeholder' => trans('general.form.enter', ['field' => trans('modules.api_key')]),
                                    'v-model' => 'form.api_key'
                                ], [])) !!}
                            </div>

                            <div class="invalid-feedback d-block" v-if="form.errors.has('api_key')" v-html="form.errors.get('api_key')"></div>
                        </div>
                        <p class="mb-0 mt--3">
                            <small>{!! trans('modules.get_api_key', ['url' => 'https://akaunting.com/dashboard']) !!}</small>
                        </p>
                        <br>
                    </div>

                    {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                    {{ Form::dateGroup('financial_start', trans('settings.localisation.financial_start'), 'calendar', ['id' => 'financial_start', 'class' => 'form-control datepicker', 'show-date-format' => 'j F', 'date-format' => 'd-m', 'autocomplete' => 'off'], Date::now()->startOfYear()->format('d-m')) }}

                    {{ Form::textareaGroup('address', trans('settings.company.address')) }}

                    {{ Form::fileGroup('logo', trans('settings.company.logo')) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        {!! Form::button(
                            '<div v-if="form.loading" class="aka-loader-frame"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>' . '<span v-if="!form.loading" class="btn-inner--text"> ' . trans('general.save') . '</span>',
                                [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success button-submit header-button-top', 'data-loading-text' => trans('general.loading')]) !!}

                        <a href="{{ url('wizard/currencies') }}" id="wizard-skip" class="btn btn-icon btn-white header-button-top">
                            <span class="btn-inner--icon"><i class="fas fa-arrow-right"></i></span>
                            <span class="btn-inner--text">{{ trans('general.skip') }}</span>
                        </a>
                    </div>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/wizard/company.js?v=' . version('short')) }}"></script>
@endpush
