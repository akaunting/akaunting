@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm">{{ trans('modules.api_key') }}</a>
    <a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm">{{ trans('modules.my_apps') }}</a>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="card">
        <div class="card-body">
            @if ($documentation)
                {!! $documentation->body !!}
            @else
                {{ trans('general.na') }}
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ url($back) }}" class="btn btn-white">{{ trans('modules.back') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/apps.js?v=' . version('short')) }}"></script>
@endpush
