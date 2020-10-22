@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span><a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}</a></span>
    <span><a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
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
                    <a href="{{  url($back) }}" class="btn btn-white header-button-top"><span class="fas fa-arrow-left"></span> &nbsp;{{ trans('modules.back') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/apps.js?v=' . version('short')) }}"></script>
@endpush
