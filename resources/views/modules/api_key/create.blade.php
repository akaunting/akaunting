@extends('layouts.modules')

@section('title', trans('modules.api_key'))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'apps.api-key.store',
            'id' => 'app',
            '@submit.prevent' => 'onSubmit',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

            <div class="card-body">
                <div class="col-md-12">
                    <div class="form-group required {{ $errors->has('api_key') ? 'has-error' : ''}}">
                        {!! Form::label('api_key', trans('modules.api_key'), ['class' => 'form-control-label']) !!}
                        <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-key"></i>
                                </span>
                            </div>
                            {!! Form::text('api_key', setting('apps.api_key', null), ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.enter', ['field' => trans('modules.api_key')])]) !!}
                        </div>
                        {!! $errors->first('api_key', '<p class="help-block">:message</p>') !!}
                    </div>

                    <p class="mb-0 mt--3">
                        <small>{!! trans('modules.api_key_link') !!}</small>
                    </p>
                </div>
            </div>

            <div class="card-footer">
                <div class="float-right">
                    {{ Form::saveButtons('apps/home') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/apps.js?v=' . version('short')) }}"></script>
@endpush
