@extends('layouts.portal')

@section('title', trans('general.title.edit', ['type' => trans('auth.profile')]))

@section('content')
    <div class="card">
        {!! Form::model($user, [
            'id' => 'profile',
            'method' => 'PATCH',
            'route' => ['portal.profile.update', $user->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('name', trans('general.name'), 'user') }}

                {{ Form::emailGroup('email', trans('general.email'), 'envelope') }}

                {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], $user->contact->tax_number) }}

                {{ Form::textGroup('phone', trans('general.phone'), 'phone', [], $user->contact->phone) }}

                {{ Form::textareaGroup('address', trans('general.address'), [], $user->contact->address) }}

                {{ Form::passwordGroup('password', trans('auth.password.current'), 'key', []) }}

                {{ Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', []) }}

                {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), $user->locale) }}

                {{ Form::fileGroup('picture',  trans_choice('general.pictures', 1), '', ['dropzone-class' => 'form-file']) }}
            </div>
        </div>

        @canany(['update-portal-profile'])
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('portal.dashboard') }}
                </div>
            </div>
        @endcanany

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/profile.js?v=' . version('short')) }}"></script>
@endpush

