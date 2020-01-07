@extends('layouts.error')

@section('title', trans('errors.title.403'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0 text-danger"><i class="fa fa-exclamation-triangle text-danger"></i>&nbsp;&nbsp;{{ trans('errors.header.403') }}</h2>
        </div>
        <div class="card-body">
            <p>{{ trans('errors.message.403') }}</p>

            <a href="{{ url('/') }}" class="btn btn-success header-button-top"><span class="fa fa-tachometer-alt"></span> &nbsp;{{ trans('general.go_to', ['name' => trans_choice('general.dashboards', 1)]) }}</a>
        </div>
    </div>
@endsection
