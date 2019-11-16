@extends('layouts.admin')

@include($class->views['header'])

@section('content')
    <div class="card">
        @include($class->views['filter'])

        @if($class->report->chart)
            @include($class->views['chart'])
        @endif

        @include($class->views['content'])
    </div>
@endsection
