@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button"><a href="{{ url('apps/token/create') }}" class="btn btn-success btn-sm"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_token') }}</a></span>
    <span class="new-button"><a href="{{ url('apps/my')  }}" class="btn btn-default btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        @if ($pre_sale)
        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.pre_sale') }}</h3>
            </div>

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
        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.top_paid') }}</h3>
            </div>

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
        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.new') }}</h3>
            </div>

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
        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.top_free') }}</h3>
            </div>

            @if ($free->data)
                @foreach ($free->data as $module)
                    @include('partials.modules.item')
                @endforeach
            @else
                @include('partials.modules.no_apps')
            @endif
        </div>
        @endif
    </div>
@endsection