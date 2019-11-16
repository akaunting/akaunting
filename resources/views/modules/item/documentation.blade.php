@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button"><a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}</a></span>
    <span class="new-button"><a href="{{ route('apps.my.index')  }}" class="btn btn-white btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-12">
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
                            <div class="text-left">
                                <a href="{{  url($back) }}" class="btn btn-white btn-md text-left">&laquo; {{ trans('modules.back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
