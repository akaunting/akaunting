@extends('layouts.admin')

@section('title', trans_choice('general.settings',2))

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                @can('read-settings-company')
                    <div class="col-md-4">
                        <a href="{{ route('settings.company.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fa fa-building" ></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.companies', 1) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.company.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-localisation')
                    <div class="col-md-4">
                        <a href="{{ route('settings.localisation.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.localisations', 1) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.localisation.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-invoice')
                    <div class="col-md-4">
                        <a href="{{ route('settings.invoice.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.invoices', 1) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.invoice.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-defaults')
                    <div class="col-md-4">
                        <a href="{{ route('settings.default.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fa fa-sliders-h"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.defaults', 1) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.default.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-email')
                    <div class="col-md-4">
                        <a href="{{ route('settings.email.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans('general.email') }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.email.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-schedule')
                    <div class="col-md-4">
                        <a href="{{ route('settings.schedule.edit') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fas fa-history"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans('settings.scheduling.name') }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.scheduling.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-categories')
                    <div class="col-md-4">
                        <a href="{{ route('categories.index') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fa fa-folder"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.categories', 2) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.categories.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan


                @can('read-settings-currencies')
                    <div class="col-md-4">
                        <a href="{{ route('currencies.index') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fa fa-dollar-sign"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.currencies', 2) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.currencies.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @can('read-settings-taxes')
                    <div class="col-md-4">
                        <a href="{{ route('taxes.index') }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="fas fa-percent"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ trans_choice('general.taxes', 2) }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ trans('settings.taxes.description') }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endcan

                @foreach($modules as $module)
                    <div class="col-md-4">
                        <a href="{{ url($module['url']) }}">
                            <button type="button" class="btn-icon-clipboard p-2">
                                <div class="row mx-0">
                                    <div class="col-auto">
                                        <div class="badge badge-secondary settings-icons">
                                            <i class="{{ $module['icon'] }}"></i>
                                        </div>
                                    </div>
                                    <div class="col ml--2">
                                        <h4 class="mb-0">{{ $module['name'] }}</h4>
                                        <p class="text-sm text-muted mb-0">{{ $module['description'] }}</p>
                                    </div>
                                </div>
                            </button>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
