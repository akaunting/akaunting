@extends('layouts.install')

@section('header', trans('install.steps.settings'))

@section('content')
    <div class="row">
        {{ Form::textGroup('company_name', trans('install.settings.company_name'), 'building', ['required' => 'required'], old('company_name'), 'col-md-12') }}

        {{ Form::textGroup('company_email', trans('install.settings.company_email'), 'envelope', ['required' => 'required'], old('company_email'), 'col-md-12') }}

        {{ Form::textGroup('user_email', trans('install.settings.admin_email'), 'envelope', ['required' => 'required'], old('user_email'), 'col-md-12') }}

        {{ Form::passwordGroup('user_password', trans('install.settings.admin_password'), 'key', ['required' => 'required'], 'col-md-12 mb--2') }}
    </div>
@endsection
