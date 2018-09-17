@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.permissions', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'auth/permissions', 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('display_name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('name', trans('general.code'), 'code') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('auth/permissions') }}
        </div>
        <!-- /.box-footer -->
        {!! Form::close() !!}
    </div>
@endsection
