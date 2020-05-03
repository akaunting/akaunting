@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <span><a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-key"></span> &nbsp;{{ trans('modules.api_key') }}</a></span>
    <span><a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-user"></span> &nbsp;{{ trans('modules.my_apps') }}</a></span>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-8">
            <h3>{{ $module->name }}</h3>

            <div class="nav-wrapper">
                <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-sm-2 mb-md-0 active" href="#description" data-toggle="tab" aria-selected="false">{{ trans('general.description') }}</a>
                    </li>
                </ul>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="description">
                            {!! $module->description !!}

                            @if ($module->screenshots || $module->video)
                               <akaunting-carousel :name="'{{ $module->name }}'" :height="'430px'"
                                    @if ($module->video)
                                        @php
                                            if (strpos($module->video->link, '=') !== false) {
                                                $code = explode('=', $module->video->link);
                                                $code[1]= str_replace('&list', '', $code[1]);
                                            }
                                        @endphp
                                        :video="'{{ $code[1] }}'"
                                    @endif
                                    :screenshots="{{ json_encode($module->screenshots) }}">
                                </akaunting-carousel>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <h3>{{ trans_choice('general.actions', 1) }}</h3>

            <div class="card">
                <div class="card-body">
                    <div id="countdown-pre-sale"></div>
                    <div class="text-center">
                        <strong>
                            <div class="text-xl">
                                @if ($module->price == '0.0000')
                                    {{ trans('modules.free') }}
                                @else
                                    {!! $module->price_prefix !!}
                                    @if (isset($module->special_price))
                                        <del class="text-danger">{{ $module->price }}</del>
                                        {{ $module->special_price }}
                                    @else
                                        {{ $module->price }}
                                    @endif
                                    {!! $module->price_suffix !!}
                                @endif
                            </div>
                        </strong>
                    </div>
                </div>

                <div class="card-footer">
                    @permission('create-modules-item')
                        @if ($module->install)
                            <a href="#" class="btn btn-warning btn-block" disabled="disabled">
                                {{ trans('modules.pre_sale') }}
                            </a>
                        @else
                            <a href="{{ $module->action_url }}" class="btn btn-warning btn-block" target="_blank">
                                {{ trans('modules.pre_sale') }}
                            </a>
                        @endif
                    @endpermission

                    @if (!empty($module->purchase_desc))
                        <div class="text-center mt-3">
                            {!! $module->purchase_desc !!}
                        </div>
                    @endif
                </div>
            </div>

            <h3>{{ trans('modules.about') }}</h3>

            <div class="card">
                <table class="table">
                    <tbody>
                        @if ($module->vendor_name)
                            <tr>
                                <th>{{ trans_choice('general.developers', 1) }}</th>
                                <td class="text-right"><a href="{{ route('apps.vendors.show', $module->vendor->slug) }}">{{ $module->vendor_name }}</a></td>
                            </tr>
                        @endif
                        @if ($module->version)
                            <tr>
                                <th>{{ trans('footer.version') }}</th>
                                <td class="text-right">{{ $module->version }}</td>
                            </tr>
                        @endif
                        @if ($module->created_at)
                            <tr>
                                <th>{{ trans('modules.added') }}</th>
                                <td class="text-right">@date($module->created_at)</td>
                            </tr>
                        @endif
                        @if ($module->updated_at)
                            <tr>
                                <th>{{ trans('modules.updated') }}</th>
                                <td class="text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                            </tr>
                        @endif
                        @if ($module->category)
                            <tr>
                                <th>{{ trans_choice('general.categories', 1) }}</th>
                                <td class="text-right"><a href="{{ route('apps.categories.show', $module->category->slug) }}">{{ $module->category->name }}</a></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($module->purchase_faq)
        <akaunting-modal :show="faq.show">
            <template #modal-content>
                {!! $module->purchase_faq !!}
            </template>
        </akaunting-modal>
    @endif
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var app_slug = "{{ $module->slug }}";
    </script>

    <script src="{{ asset('public/js/modules/item.js?v=' . version('short')) }}"></script>
@endpush
