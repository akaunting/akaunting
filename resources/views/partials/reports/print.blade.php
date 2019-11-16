@extends('layouts.print')

@section('title', $class->report->name)

@section('content')
    @if($class->report->chart)
        @include($class->views['chart'])
    @endif

    @include($class->views['content'])
@endsection
