@extends('layouts.admin')

@section('title', trans('errors.error_page'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0 text-danger">500 Internal Server Error</h2>
        </div>
        <div class="card-body">
            <h3><i class="fa fa-exclamation-triangle text-danger"></i> {{ trans('errors.body.error_page') }}</h3>

            <p>{!! trans('errors.messages.error_page', ['link' => url('/') ]) !!}</p>
        </div>
    </div>
@endsection
