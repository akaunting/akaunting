@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.permissions', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($permission, [
            'id' => 'permission',
            'method' => 'PATCH',
            'route' => ['permissions.update', $permission->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('display_name', trans('general.name'), 'font') }}

                    {{ Form::textGroup('name', trans('general.code'), 'code') }}

                    {{ Form::textareaGroup('description', trans('general.description')) }}
                </div>
            </div>

            @permission('update-auth-permissions')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('permissions.index') }}
                    </div>
                </div>
            @endpermission
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/auth/permissions.js?v=' . version('short')) }}"></script>
@endpush
