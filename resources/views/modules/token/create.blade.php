@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('modules.enter_api_token') }}</h3>
        </div>
        <!-- /.box-header -->

        <!-- form start -->
        {!! Form::open(['url' => 'modules/token', 'files' => true, 'role' => 'form']) !!}

        <div class="box-body">
            <div class="form-group required {{ $errors->has('api_token') ? 'has-error' : ''}}">
                {!! Form::label('sale_price', trans('modules.api_token'), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    {!! Form::text('api_token', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.enter', ['field' => trans('modules.api_token')])]) !!}
                </div>
                {!! $errors->first('api_token', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('modules/token') }}
        </div>

        {!! Form::close() !!}
    </div>
@endsection