@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button"><a href="{{ url('apps/token/create') }}" class="btn btn-success btn-sm"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_token') }}</a></span>
    <span class="new-button"><a href="{{ url('apps/my')  }}" class="btn btn-default btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ $title }}</h3>
            </div>

            @if ($modules)
                @foreach ($modules->data as $module)
                    @if ($module->status_type == 'pre_sale')
                        @include('partials.modules.pre_sale')
                    @else
                        @include('partials.modules.item')
                    @endif
                @endforeach
                <div class="col-md-12 no-padding-left">
                    <ul class="pager nomargin">
                        @if ($modules->current_page < $modules->last_page)
                            <li class="next"><a href="{{ url(request()->path()) }}?page={{ $modules->current_page + 1 }}" class="btn btn-default btn-sm">{{ trans('pagination.next') }}</a></li>
                        @endif
                        @if ($modules->current_page > 1)
                            <li class="previous"><a href="{{ url(request()->path()) }}?page={{ $modules->current_page - 1 }}" class="btn btn-default btn-sm">{{ trans('pagination.previous') }}</a></li>
                        @endif
                    </ul>
                </div>
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
    </div>
@endsection
