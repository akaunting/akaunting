@extends('layouts.admin')

@section('title', trans('errors.page_not_found'))

@section('content')
<div class="error-page">
    <h2 class="headline text-yellow"> 404</h2>

    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> {{ trans('errors.body.page_not_found') }}</h3>

        <p>{!! trans('errors.messages.page_not_found', ['link' => url('/')]) !!}</p>
    </div>
</div>
@endsection
