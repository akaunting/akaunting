@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span><a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}</a></span>
    <span><a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    @if ($pre_sale)
        <h2>{{ trans('modules.pre_sale') }}</h2>

        <div class="row">
            @if ($pre_sale->data)
                @foreach ($pre_sale->data as $module)
                    @include('partials.modules.pre_sale')
                @endforeach
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
    @endif

    @if ($paid)
        <h2>{{ trans('modules.top_paid') }}</h2>

        <div class="row">
            @if ($paid->data)
                @foreach ($paid->data as $module)
                    @include('partials.modules.item')
                @endforeach
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
    @endif

    @if ($new)
        <h2>{{ trans('modules.new') }}</h2>

        <div class="row">
            @if ($new->data)
                @foreach ($new->data as $module)
                    @include('partials.modules.item')
                @endforeach
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
    @endif

    @if ($free)
        <h2>{{ trans('modules.top_free') }}</h2>

        <div class="row">
            @if ($free->data)
                @foreach ($free->data as $module)
                    @include('partials.modules.item')
                @endforeach
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/apps.js?v=' . version('short')) }}"></script>
@endpush
