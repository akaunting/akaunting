@extends('layouts.admin')

@section('title', trans('errors.forbidden_access'))

@section('content')
<div class="error-page">
    <h2 class="headline text-red">403</h2>

    <div class="error-content">
        <h3><i class="fa fa-ban text-red"></i> {{ trans('errors.body.forbidden_access') }}</h3>

        <p>{!! trans('errors.messages.forbidden_access', ['link' => url('/') ]) !!}</p>
    </div>
</div>
@endsection
