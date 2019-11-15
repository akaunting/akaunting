@extends('layouts.admin')

@section('title', trans('errors.forbidden_access'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0 text-warning">403 Forbidden</h2>
        </div>
        <div class="card-body">
            <h3><i class="fa fa-exclamation-triangle text-danger"></i> {{ trans('errors.body.forbidden_access') }}</h3>

            <p>{!! trans('errors.messages.forbidden_access', ['link' => url('/') ]) !!}</p>
        </div>
    </div>
@endsection
