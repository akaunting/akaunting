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
            <div class="col-md-8 no-padding-left">
                <div class="content-header no-padding-left">
                    <h3>{{ $module->name }}</h3>
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#description" data-toggle="tab" aria-expanded="true">{{ trans('general.description') }}</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="description">
                            {!! $module->description !!}

                            @if($module->screenshots || $module->video)
                                <div id="carousel-screenshot-generic" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        @if($module->video)
                                            @php
                                            if (strpos($module->video->link, '=') !== false) {
                                                $code = explode('=', $module->video->link);
                                                $code[1]= str_replace('&list', '', $code[1]);

                                                if (empty($status)) {
                                                    $status = 5;
                                                } else {
                                                    $status = 1;
                                                } 
                                            @endphp

                                            <div class="item @if($status == 5) {{ 'active' }} @endif">
                                                <iframe width="100%" height="410px" src="https://www.youtube-nocookie.com/embed/{{ $code[1] }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                                                <div class="image-description text-center">
                                                    {{ $module->name }}
                                                </div>
                                            </div>
                                            @php } @endphp
                                        @endif

                                        @foreach($module->screenshots as $screenshot)
                                            @php if (empty($status)) { $status = 5; } else { $status = 1; } @endphp
                                            <div class="item @if($status == 5) {{ 'active' }} @endif">
                                                <a href="{{ $screenshot->path_string }}" data-toggle="lightbox" data-gallery="{{ $module->slug}}">
                                                    <img class="img-fluid d-block w-100" src="{{ $screenshot->path_string }}" alt="{{ $screenshot->alt_attribute }}">
                                                </a>

                                                <div class="image-description text-center">
                                                    {{ $screenshot->description }}
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="carousel-navigation-message">
                                            @if (($module->video && (count($module->screenshots) > 1)) || (!$module->video && (count($module->screenshots) > 1)))
                                            <a href="#carousel-screenshot-generic" class="left carousel-control" role="button" data-slide="prev">
                                                <i class="fa fa-chevron-left"></i>
                                                <span class="sr-only">{{ trans('pagination.previous') }}</span>
                                            </a>
                                            <a href="#carousel-screenshot-generic" class="right carousel-control" role="button" data-slide="next">
                                                <i class="fa fa-chevron-right"></i>
                                                <span class="sr-only">{{ trans('pagination.next') }}</span>
                                            </a>
                                            @endif()
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="content-header no-padding-left">
                    <h3>{{ trans_choice('general.actions', 1) }}</h3>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <div id="countdown-pre-sale"></div>

                        <div class="text-center action">
                            <div style="margin: 10px; font-size: 24px;">
                                @if ($module->price == '0.0000')
                                    {{ trans('modules.free') }}
                                @else
                                    {!! $module->price_prefix !!}
                                    @if (isset($module->special_price))
                                        <del>{{ $module->price }}</del>
                                        {{ $module->special_price }}
                                    @else
                                        {{ $module->price }}
                                    @endif
                                    {!! $module->price_suffix !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
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

                        @if ($module->purchase_faq)
                        </br>
                        <div class="text-center">
                            <a href="#" id="button-purchase-faq">{{ trans('modules.tab.faq')}}</a>
                        </div>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box -->

                <div class="content-header no-padding-left">
                    <h3>{{ trans('modules.about') }}</h3>
                </div>

                <div class="box box-success">
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                                @if ($module->vendor_name)
                                <tr>
                                    <th>{{ trans_choice('general.developers', 1) }}</th>
                                    <td class="text-right"><a href="{{ url('apps/vendors/' . $module->vendor->slug) }}">{{ $module->vendor_name }}</a></td>
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
                                    <td class="text-right">{{ Date::parse($module->created_at)->format($date_format) }}</td>
                                </tr>
                                @endif
                                @if ($module->updated_at)
                                <tr>
                                    <th>{{ trans('modules.updated') }}</th>
                                    <td class="text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                                </tr>
                                @endif
                                @if ($module->compatibility)
                                <tr>
                                    <th>{{ trans('modules.compatibility') }}</th>
                                    <td class="text-right">{{ $module->compatibility }}</td>
                                </tr>
                                @endif
                                @if ($module->category)
                                <tr>
                                    <th>{{ trans_choice('general.categories', 1) }}</th>
                                    <td class="text-right"><a href="{{ url('apps/categories/' . $module->category->slug) }}">{{ $module->category->name }}</a></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>

    @if ($module->purchase_faq)
    {!! $module->purchase_faq !!}
    @endif
@endsection

@push('js')
<script src="{{ asset('public/js/lightbox/ekko-lightbox.js') }}"></script>
<script src="{{ asset('public/js/jquery/countdown/jquery.plugin.js') }}"></script>
<script src="{{ asset('public/js/jquery/countdown/jquery.countdown.js') }}"></script>
@if (language()->getShortCode() != 'en')
<script src="{{ asset('public/js/jquery/countdown/jquery.countdown-' . language()->getShortCode() . '.js') }}"></script>
@endif
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('public/css/ekko-lightbox.css') }}">
<link rel="stylesheet" href="{{ asset('public/css/countdown.css') }}">
@endpush

@push('stylesheet')
    <style type="text/css">
        .nav-tabs-custom img {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .text-center.action {
            border-top: 1px solid #f4f4f4;
            margin-top: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.carousel').carousel({
                interval: false,
                keyboard: true
            });

            $('#countdown-pre-sale').countdown({
                until: new Date({{ (int) $module->pre_sale_date->year }}, {{ (int) $module->pre_sale_date->month }} - 1, {{ (int) $module->pre_sale_date->day }})
            });
        });

        $(document).on('click', '[data-toggle="lightbox"]', function(e) {
            e.preventDefault();

            $(this).ekkoLightbox();
        });

        @if ($module->purchase_faq)
        $(document).on('click', '#button-purchase-faq', function (e) {
            $('.app-faq-modal').modal('show');
        });
        @endif
    </script>
@endpush
