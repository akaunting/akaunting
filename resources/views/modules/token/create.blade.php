@extends('layouts.modules')

@section('title', trans('modules.title'))

@section('content')
    <div class="box box-success">
        {!! Form::open(['url' => 'apps/token', 'files' => true, 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            <div class="col-md-12">
                <div class="form-group required {{ $errors->has('api_token') ? 'has-error' : ''}}">
                    {!! Form::label('api_token', trans('modules.api_token'), ['class' => 'control-label']) !!}
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        {!! Form::text('api_token', setting('general.api_token', null), ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.enter', ['field' => trans('modules.api_token')])]) !!}
                    </div>
                    {!! $errors->first('api_token', '<p class="help-block">:message</p>') !!}
                </div>
                <p>
                    {!! trans('modules.token_link') !!}
                </p>
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('apps/home') }}
        </div>

        {!! Form::close() !!}
    </div>
@endsection