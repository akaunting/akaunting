@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row mb--4">
                    <hr class="wizard-line">
                    <div class="col-md-3">
                        <div class="text-center">
                           <a href="{{ url('wizard/companies') }}">
                            <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans_choice('general.companies', 1) }}</p>
                           </a>
                        </div>
                    </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <a href="{{ url('wizard/currencies') }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('general.currencies', 2) }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <a href="{{ url('wizard/taxes') }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('general.taxes', 2) }}</p>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="text-center">
                                <button href="#step-4" type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner">4</span>
                                </button>
                                <p class="mt-2 after-step-text">{{ trans_choice('general.finish', 1) }}</p>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12 text-center">
            <a href="{{ url('/') }}" class="btn btn-lg btn-success"><span class="fa fa-home"></span> &nbsp;{{ trans('general.go_to', ['name' => trans('general.dashboard')]) }}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="content-header">
                <h3 class="text-white">{{ trans('modules.recommended_apps') }}</h3>
            </div>

            @if ($modules)
                <div class="row">
                    @foreach ($modules->data as $module)
                        @include('partials.modules.item')
                    @endforeach
                </div>

                <div class="col-md-12">
                    <ul>
                        @if ($modules->current_page < $modules->last_page)
                            <li class="next"><a href="{{ url(request()->path()) }}?page={{ $modules->current_page + 1 }}" class="btn btn-default btn-sm">{{ trans('pagination.next') }}</a></li>
                        @endif
                        @if ($modules->current_page > 1)
                            <li class="previous"><a href="{{ url(request()->path()) }}?page={{ $modules->current_page - 1 }}" class="btn btn-default btn-sm">{{ trans('pagination.previous') }}</a></li>
                        @endif
                    </ul>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <p class="col-md-12">
                            {{ trans('modules.no_apps') }}
                        </p>

                        <p class="col-md-12">
                            <small>{!! trans('modules.developer') !!}</small>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
