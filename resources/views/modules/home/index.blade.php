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
                <h3>{{ trans('modules.top_paid') }}</h3>
            </div>

            @foreach ($paid as $module)
                @include('partials.modules.item')
            @endforeach
        </div>

        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.new') }}</h3>
            </div>

            @foreach ($new as $module)
                @include('partials.modules.item')
            @endforeach
        </div>

        <div class="col-md-12">
            <div class="content-header no-padding-left">
                <h3>{{ trans('modules.top_free') }}</h3>
            </div>

            @foreach ($free as $module)
                @include('partials.modules.item')
            @endforeach
        </div>
    </div>
@endsection