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

                    <div class="pull-right rating">
                        @for($i = 1; $i <= $module->vote; $i++)
                            <i class="fa fa-star fa-lg"></i>
                        @endfor
                        @for($i = $module->vote; $i < 5; $i++)
                            <i class="fa fa-star-o fa-lg"></i>
                        @endfor
                    </div>
                </div>

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#description" data-toggle="tab" aria-expanded="true">{{ trans('general.description') }}</a></li>
                        @if ($module->installation)
                        <li class=""><a href="#installation" data-toggle="tab" aria-expanded="false">{{ trans('modules.tab.installation') }}</a></li>
                        @endif
                        @if ($module->faq)
                        <li class=""><a href="#faq" data-toggle="tab" aria-expanded="false">{{ trans('modules.tab.faq') }}</a></li>
                        @endif
                        @if ($module->changelog)
                        <li class=""><a href="#changelog" data-toggle="tab" aria-expanded="false">{{ trans('modules.tab.changelog') }}</a></li>
                        @endif
                        <li><a href="#review" data-toggle="tab" aria-expanded="false">{{ trans('modules.tab.reviews') }} @if ($module->total_review) ({{ $module->total_review }}) @endif</a></li>
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
                        @if ($module->installation)
                        <div class="tab-pane" id="installation">
                            {!! $module->installation !!}
                        </div>
                        @endif
                        @if ($module->faq)
                        <div class="tab-pane" id="faq">
                            {!! $module->faq !!}
                        </div>
                        @endif
                        @if ($module->changelog)
                        <div class="tab-pane" id="changelog">
                            {!! $module->changelog !!}
                        </div>
                        @endif
                        <div class="tab-pane" id="review">
                            <div id="reviews" class="clearfix">
                                @if(!$module->reviews)
                                {{ trans('modules.reviews.na') }}
                                @endif
                            </div>

                            <hr>

                            @if (!empty($module->review_action))
                                <a href="{{ $module->review_action }}" class="btn btn-success" target="_blank">
                                    {{ trans('modules.reviews.button.add') }}
                                </a>
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
                        <div class="text-center">
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
                        @if ($installed)
                            @permission('delete-modules-item')
                            <a href="{{ url('apps/' . $module->slug . '/uninstall') }}" class="btn btn-block btn-danger">{{ trans('modules.button.uninstall') }}</a>
                            @endpermission
                            @permission('update-modules-item')
                            @if ($enable)
                                <a href="{{ url('apps/' . $module->slug . '/disable') }}" class="btn btn-block btn-warning">{{ trans('modules.button.disable') }}</a>
                            @else
                                <a href="{{ url('apps/' . $module->slug . '/enable') }}" class="btn btn-block btn-success">{{ trans('modules.button.enable') }}</a>
                            @endif
                            @endpermission
                        @else
                            @permission('create-modules-item')
                            @if ($module->install)
                            <a href="{{ $module->action_url }}" class="btn btn-success btn-block" id="install-module">
                                {{ trans('modules.install') }}
                            </a>
                            @else
                            <a href="{{ $module->action_url }}" class="btn btn-success btn-block" target="_blank">
                                {{ trans('modules.buy_now') }}
                            </a>
                            @endif
                            @endpermission
                        @endif

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

                                <tr>
                                    <th>{{ trans('modules.documentation') }}</th>
                                    <td class="text-right">
                                        @if ($module->documentation)
                                            <a href="{{ url('apps/docs/' . $module->slug) }}">{{ trans('modules.view') }}</a></td>
                                        @else
                                            {{ trans('general.na') }}
                                        @endif
                                </tr>
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
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('public/css/ekko-lightbox.css') }}">
@endpush

@push('style')
    <style type="text/css">
    .nav-tabs-custom img {
        display: block;
        max-width: 100%;
        height: auto;
    }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var step = new Array();
        var total = 0;
        var path = '';

        $(document).ready(function() {
            $('.carousel').carousel({
                interval: false,
                keyboard: true
            });

            @if($module->reviews)
            getReviews('', '1');
            @endif

            $('#install-module').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                path = $(this).attr('href');

                startInstallation();

                $.ajax({
                    url: '{{ url("apps/steps") }}',
                    type: 'post',
                    dataType: 'json',
                    data: {name: '{{ $module->name }}', version: '{{ $module->version }}'},
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(json) {
                        if (json['errorr']) {
                            $('#progress-bar').addClass('progress-bar-danger');
                            $('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['step']) {
                            step = json['step'];
                            total = step.length;

                            next();
                        }
                    }
                });
            });
        });

        $(document).on('click', '#reviews .pagination li a', function (e) {
            e.preventDefault();
            e.stopPropagation();

            path = $(this).attr('href');
            page = $(this).data('page');

            getReviews(path, page);
        });

        $(document).on('click', '[data-toggle="lightbox"]', function(e) {
            e.preventDefault();

            $(this).ekkoLightbox();
        });

        function next() {
            data = step.shift();

            if (data) {
                $('#progress-bar').css('width', (100 - (step.length / total) * 100) + '%');
                $('#progress-text').html('<span class="text-info">' + data['text'] + '</span>');

                setTimeout(function() {
                    $.ajax({
                        url: data.url,
                        type: 'post',
                        dataType: 'json',
                        data: {path: path, version: '{{ $module->version }}'},
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function(json) {
                            if (json['errors']) {
                                $('#progress-bar').addClass('progress-bar-danger');
                                $('#progress-text').html('<div class="text-danger">' + json['errors'] + '</div>');
                            }

                            if (json['success']) {
                                $('#progress-bar').removeClass('progress-bar-danger');
                                $('#progress-bar').addClass('progress-bar-success');
                            }

                            if (json['data']['path']) {
                                path = json['data']['path'];
                            }

                            if (!json['errors'] && !json['installed']) {
                                next();
                            }

                            if (json['installed']) {
                                window.location = json['installed'];
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });
                }, 800);
            }
        }

        function startInstallation() {
            $('#modal-installation').remove();

            modal  = '<div class="modal fade" id="modal-installation" style="display: none;">';
            modal += '  <div class="modal-dialog">';
            modal += '      <div class="modal-content">';
            modal += '          <div class="modal-header">';
            modal += '              <h4 class="modal-title">{{ trans('modules.installation.header') }}</h4>';
            modal += '          </div>';
            modal += '          <div class="modal-body">';
            modal += '              <p></p>';
            modal += '              <p>';
            modal += '                 <div class="progress">';
            modal += '                  <div id="progress-bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">';
            modal += '                      <span class="sr-only">{{ trans('modules.installation.start', ['module' => $module->name]) }}</span>';
            modal += '                  </div>';
            modal += '                 </div>';
            modal += '                 <div id="progress-text"></div>';
            modal += '              </p>';
            modal += '          </div>';
            modal += '      </div>';
            modal += '  </div>';
            modal += '</div>';

            $('body').append(modal);

            $('#modal-installation').modal('show');
        }

        function getReviews(path, page) {
            $.ajax({
                url: '{{ url("apps/" . $module->slug . "/reviews") }}',
                type: 'post',
                dataType: 'json',
                data: {path: path, page: page},
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                beforeSend: function() {
                    $('#reviews').append('<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>');
                },
                complete : function() {
                    $('#loading').remove();
                },
                success: function(json) {
                    if (json['success']) {
                        $('#reviews #review-items').remove();
                        $('#reviews').append(json['html']);
                    }
                }
            });
        }

        @if ($module->purchase_faq)
        $(document).on('click', '#button-purchase-faq', function (e) {
            $('.app-faq-modal').modal('show');
        });
        @endif
    </script>
@endpush
