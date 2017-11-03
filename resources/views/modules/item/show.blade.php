@extends('layouts.modules')

@section('title', trans_choice('general.modules', 2))

@section('content')
    @include('partials.modules.bar')

    <div class="col-md-12 no-padding-left">
        <div class="col-md-8 no-padding-left">
            <div class="content-header no-padding-left">
                <h3>{{ $module->name }}</h3>
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
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="description">
                        {!! $module->description !!}
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
                </div>
            </div>
        </div>

        <div class="col-md-4 no-padding-right">
            <div class="content-header no-padding-left">
                <h3>{{ trans_choice('general.actions', 1) }}</h3>
            </div>

            <div class="box box-success">
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Price</th>
                                <td class="text-right">
                                    @if ($module->price == '0.0000')
                                        {{ trans('modules.free') }}
                                    @else
                                        {{ $module->price . ' / month' }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    @if ($installed)
                        <a href="{{ url('apps/' . $module->slug . '/uninstall') }}" class="btn btn-block btn-danger">{{ trans('modules.button.uninstall') }}</a>
                        @if ($enable)
                            <a href="{{ url('apps/' . $module->slug . '/disable') }}" class="btn btn-block btn-warning">{{ trans('modules.button.disable') }}</a>
                        @else
                            <a href="{{ url('apps/' . $module->slug . '/enable') }}" class="btn btn-block btn-success">{{ trans('modules.button.enable') }}</a>
                        @endif
                    @else
                        @if ($module->install)
                        <a href="{{ $module->action_url }}" class="btn btn-success btn-block" id="install-module">
                            {{ trans('modules.install') }}
                        </a>
                        @else
                        <a href="{{ $module->action_url }}" class="btn btn-success btn-block" target="_blank">
                            {{ trans('modules.buy_now') }}
                        </a>
                        @endif
                    @endif
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->

            <div class="content-header no-padding-left">
                <h3>About</h3>
            </div>

            <div class="box box-success">
                <div class="box-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Vendor</th>
                                <td class="text-right"><a href="{{ url('apps/vendor/' . $module->vendor->id) }}">{{ $module->vendor_name }}</a></td>
                            </tr>
                            <tr>
                                <th>Version</th>
                                <td class="text-right">{{ $module->version }}</td>
                            </tr>
                            <tr>
                                <th>Added</th>
                                <td class="text-right">{{ Date::parse($module->created_at)->format($date_format) }}</td>
                            </tr>
                            <tr>
                                <th>Updated</th>
                                <td class="text-right">{{ Date::parse($module->updated_at)->diffForHumans() }}</td>
                            </tr>
                            <tr>
                                <th>Compatibility</th>
                                <td class="text-right">{{ $module->compatibility }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td class="text-right"><a href="{{ url('apps/category/' . $module->category->slug) }}">{{ $module->category->name }}</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var step = new Array();
        var total = 0;
        var path = '';

        $(document).ready(function() {
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
                                window.location = '{{ url("apps/" . $module->slug) }}';
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
    </script>
@endsection
