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
                @foreach ($modules as $module)
                    @include('partials.modules.item')
                @endforeach
            @else
                <div class="box box-success">
                    <div class="box-body">
                        <p class="col-md-12" style="margin-top: 15px">
                            {{ trans('modules.no_apps') }}
                        </p>
                        <p class="col-md-12" style="margin-top: 20px">
                            <small>{!! trans('modules.developer') !!}</small>
                        </p>
                    </div>
                    <!-- /.box-body -->
                </div>
            @endif
        </div>
    </div>
@endsection