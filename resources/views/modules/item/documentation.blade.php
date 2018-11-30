@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span class="new-button"><a href="{{ url('apps/token/create') }}" class="btn btn-success btn-sm"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_token') }}</a></span>
    <span class="new-button"><a href="{{ url('apps/my')  }}" class="btn btn-default btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row module">
        <div class="col-md-12">
            <div class="col-md-12 no-padding-left">
                <div class="box box-success">
                    <div class="box-body">
                    @if ($documentation)
                        {!! $documentation->body !!}
                    @else
                        {{ trans('general.na') }}
                    @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12 no-padding-left">
                <ul class="pager nomargin">
                    <li class="previous"><a href="{{  url($back) }}" class="btn btn-default btn-sm">&laquo; {{ trans('modules.back') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('stylesheet')
<style type="text/css">
    .row.module h1 {
        margin: 0;
        font-size: 24px !important;
    }

    .row.module img {
        width: 100%;
        max-width: 900px;
    }
</style>
@endpush