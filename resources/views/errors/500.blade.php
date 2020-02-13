@extends('layouts.error')

@section('title', trans('errors.title.500'))

@section('content')
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0 text-danger"><i class="fa fa-exclamation-triangle text-danger"></i>&nbsp;&nbsp;{{ trans('errors.header.500') }}</h2>
        </div>
        <div class="card-body">
            <p>{{ trans('errors.message.500') }}</p>

            @php $landing_page = user() ? route(user()->landing_page) : route('login'); @endphp

            <a href="{{ $landing_page }}" class="btn btn-success header-button-top"><span class="fa fa-tachometer-alt"></span> &nbsp;{{ trans('general.go_to_dashboard') }}</a>
        </div>
    </div>
@endsection
