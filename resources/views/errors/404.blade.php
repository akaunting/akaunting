@extends('layouts.admin')

@section('title', trans('errors.page_not_found'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0 text-yellow">404 Not Found</h2>
        </div>
        <div class="card-body">
            <h3><i class="fa fa-exclamation-triangle text-yellow"></i> {{ trans('errors.body.page_not_found') }}</h3>

            <p>{!! trans('errors.messages.page_not_found', ['link' => url('/')]) !!}</p>
        </div>
    </div>
@endsection
