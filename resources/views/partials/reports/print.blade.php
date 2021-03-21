@extends('layouts.print')

@section('title', $class->model->name)

@section('content')
    <h2>{{ $class->model->name }}</h2>

    {{ setting('company.name') }}

    @if (!empty($class->model->settings->chart))
        @include($class->views['chart'])
    @endif

    @include($class->views['content'])
@endsection
