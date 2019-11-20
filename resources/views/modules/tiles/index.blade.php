@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button"><a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}</a></span>
    <span class="new-button"><a href="{{ route('apps.my.index')  }}" class="btn btn-white btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')
        <h2 class="text-primary">{{ $title }}</h2>
        <div class="row">
            @if ($modules)
                @foreach ($modules->data as $module)
                    @if ($module->status_type == 'pre_sale')
                        @include('partials.modules.pre_sale')
                    @else
                        @include('partials.modules.item')
                    @endif
                @endforeach
        </div>

                <div class="row justify-content-center mb-4">
                    <div class="col-md-6 text-left">
                        @if ($modules->current_page > 1)
                            <a href="{{ url(request()->path()) }}?page={{ $modules->current_page - 1 }}" class="btn btn-white btn-sm">{{ trans('pagination.previous') }}</a>
                        @endif
                    </div>

                    <div class="col-md-6 text-right">
                        @if ($modules->current_page < $modules->last_page)
                            <a href="{{ url(request()->path()) }}?page={{ $modules->current_page + 1 }}" class="btn btn-white btn-sm">{{ trans('pagination.next') }}</a>
                        @endif
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    @include('partials.modules.no_apps')
                </div>
            @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/modules/apps.js?v=' . version('short')) }}"></script>
@endpush

