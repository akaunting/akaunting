@extends('layouts.print')

@section('title', $class->model->name)

@section('content')
    @if($class->model->settings->chart)
        @include($class->views['chart'])
    @endif

    @include($class->views['content'])
@endsection
