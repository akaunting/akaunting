@extends('layouts.admin')

@section('title', trans_choice('general.companies', 1))

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
        'novalidate' => true
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('settings.company.name'), 'building', ['required' => 'required'], setting('company.name')) }}

                {{ Form::textGroup('email', trans('settings.company.email'), 'envelope', ['required' => 'required'], setting('company.email')) }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], setting('company.tax_number')) }}

                {{ Form::textGroup('phone', trans('settings.company.phone'), 'phone', [], setting('company.phone')) }}

                {{ Form::textareaGroup('address', trans('settings.company.address'), null, setting('company.address')) }}

                {{ Form::fileGroup('logo', trans('settings.company.logo'), 'file-image-o', [], setting('company.logo')) }}
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

    {!! Form::hidden('_prefix', 'company') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
