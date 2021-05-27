@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('new_button')
    <a href="{{ route('apps.api-key.create') }}" class="btn btn-white btn-sm">{{ trans('modules.api_key') }}</a>
    <a href="{{ route('apps.my.index') }}" class="btn btn-white btn-sm">{{ trans('modules.my_apps') }}</a>
@endsection

@section('content')
    @include('partials.modules.bar')

    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="float-left">
                        <h3>{{ $module->name }}</h3>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="description">
                            {!! $module->description !!}

                            @if ($module->screenshots || $module->video)
                               <akaunting-carousel :name="'{{ $module->name }}'" :height="'430px'" arrow="always"
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
                    <akaunting-countdown id="countdown-pre-sale"
                        :year="{{ (int) $module->pre_sale_date->year }}"
                        :month="{{ (int) $module->pre_sale_date->month - 1 }}"
                        :date="{{ (int) $module->pre_sale_date->day }}"
                    ></akaunting-countdown>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
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
                    @can('create-modules-item')
                        @if ($module->install)
                            <a href="javascript:void(0)" class="btn btn-warning btn-block" disabled="disabled">
                                {{ trans('modules.pre_sale') }}
                            </a>
                        @else
                            <a href="{{ $module->action_url }}" class="btn btn-warning btn-block" target="_blank">
                                {{ trans('modules.pre_sale') }}
                            </a>
                        @endif
                    @endcan

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
                            <tr class="row">
                                <th class="col-5">{{ trans_choice('general.developers', 1) }}</th>
                                <td class="col-7 text-right"><a href="{{ route('apps.vendors.show', $module->vendor->slug) }}">{{ $module->vendor_name }}</a></td>
                            </tr>
                        @endif
                        @if ($module->version)
                            <tr class="row">
                                <th class="col-5">{{ trans('footer.version') }}</th>
                                <td class="col-7 text-right">{{ $module->version }}</td>
                            </tr>
                        @endif
                        @if ($module->created_at)
                            <tr class="row">
                                <th class="col-5">{{ trans('modules.added') }}</th>
                                <td class="col-7 text-right long-texts">@date($module->created_at)</td>
                            </tr>
                        @endif
                        @if ($module->updated_at)
                            <tr class="row">
                                <th class="col-5">{{ trans('modules.updated') }}</th>
                                <td class="col-7 text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                            </tr>
                        @endif
                        @if ($module->categories)
                            <tr class="row">
                                <th class="col-5">{{ trans_choice('general.categories', (count($module->categories) > 1) ? 2 : 1) }}</th>
                                <td class="col-7 text-right">
                                    @foreach ($module->categories as $module_category)
                                        <a href="{{ route('apps.categories.show', $module_category->slug) }}">{{ $module_category->name }}</a> </br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($module->purchase_faq)
        <akaunting-modal :show="faq" modal-dialog-class="modal-md">
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
