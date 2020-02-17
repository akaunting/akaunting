@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.dashboards', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'id' => 'dashboard',
            'route' => 'dashboards.store',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    @permission('read-auth-users')
                        {{ Form::checkboxGroup('users', trans_choice('general.users', 2), $users, 'name') }}
                    @endpermission

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('dashboards.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/dashboards.js?v=' . version('short')) }}"></script>
@endpush
