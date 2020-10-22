@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm">{{ trans('modules.api_key') }}</a>
    <a href="{{ route('apps.my.index')  }}" class="btn btn-white btn-sm">{{ trans('modules.my_apps') }}</a>
@endsection

@section('content')
    @include('partials.modules.bar')

    <h2>{{ trans('modules.my.purchased') }}</h2>

    <div class="row">
        @if ($purchased)
            @foreach ($purchased as $module)
                @include('partials.modules.item')
            @endforeach
        @else
            <div class="col-md-12">
                @include('partials.modules.no_apps')
            </div>
        @endif
    </div>

    <h2>{{ trans('modules.my.installed') }}</h2>

    <div class="row">
        @if ($modules)
            @foreach ($modules as $module)
                @include('partials.modules.item')
            @endforeach
        @else
            <div class="col-md-12">
                @include('partials.modules.no_apps')
            </div>
        @endif
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/apps.js?v=' . version('short')) }}"></script>
@endpush
