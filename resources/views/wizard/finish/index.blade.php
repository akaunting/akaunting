@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
<!-- Default box -->
<div class="box box-solid">
    <div class="box-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/companies') }}" type="button" class="btn btn-default btn-circle">1</a>
                    <p><small>{{ trans_choice('general.companies', 1) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/currencies') }}" type="button" class="btn btn-default btn-circle">2</a>
                    <p><small>{{ trans_choice('general.currencies', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/taxes') }}" type="button" class="btn btn-default btn-circle">3</a>
                    <p><small>{{ trans_choice('general.taxes', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-4" type="button" class="btn btn-success btn-circle">4</a>
                    <p><small>{{ trans_choice('general.finish', 1) }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 50px;">
    <div class="col-md-12 no-padding-right text-center">
        <a href="{{ url('/') }}" class="btn btn-lg btn-success"><span class="fa fa-dashboard"></span> &nbsp;{{ trans('general.go_to', ['name' => trans('general.dashboard')]) }}</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12 no-padding-right">
        <div class="content-header no-padding-left">
            <h3>{{ trans('modules.recommended_apps') }}</h3>
        </div>

        @if ($modules)
            @foreach ($modules->data as $module)
                @include('partials.modules.item')
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

@push('css')
<link rel="stylesheet" href="{{ asset('public/css/modules.css?v=' . version('short')) }}">
@endpush

@push('scripts')
<script type="text/javascript">
    var text_yes = '{{ trans('general.yes') }}';
    var text_no = '{{ trans('general.no') }}';

    $(document).ready(function() {

    });
</script>
@endpush
