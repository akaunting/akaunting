@extends('layouts.admin')

@section('title', trans_choice('general.companies', 1))

@section('content')
    {!! Form::model($setting, [
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
                {{ Form::textGroup('name', trans('settings.company.name'), 'building') }}

                {{ Form::textGroup('email', trans('settings.company.email'), 'envelope') }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                {{ Form::textGroup('phone', trans('settings.company.phone'), 'phone', []) }}

                {{ Form::textareaGroup('address', trans('settings.company.address')) }}

                {{ Form::fileGroup('logo', trans('settings.company.logo')) }}
            </div>
        </div>

        @permission('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>

    {!! Form::hidden('_prefix', 'company') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
