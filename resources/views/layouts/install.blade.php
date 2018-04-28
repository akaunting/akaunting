<html>
    @include('partials.install.head')

    <body class="hold-transition">
        <div class="install-image"></div>

        <div class="install-content">
            <div class="install-logo">
                <img src="{{ asset('public/img/akaunting-logo-white.png') }}" alt="Akaunting" />
            </div>

            <div class="box box-success box-solid">
                <div class="box-header">
                    <div class="col-md-12">
                        <h3 class="box-title">@yield('header')</h3>
                    </div>
                </div>
                <!-- /.box-header -->

                <div id="install-form">
                    {!! Form::open(['url' => url()->current(), 'role' => 'form']) !!}

                    <div class="box-body">
                        <div id="install-loading"></div>

                        <div class="form-group">
                            <div class="col-md-12">
                                @include('flash::message')
                            </div>
                        </div>

                        @yield('content')
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <div class="form-group">
                            <div class="col-md-4 col-md-offset-8 text-right">
                                @if (Request::is('install/requirements'))
                                    <a href="{{ url('install/requirements') }}" class="btn btn-success"> {{ trans('install.refresh') }} &nbsp;<i class="fa fa-refresh"></i></a>
                                @else
                                    {!! Form::button(trans('install.next') . ' &nbsp;<i class="fa fa-arrow-right"></i>', ['type' => 'submit', 'id' => 'next-button', 'class' => 'btn btn-success']) !!}
                                @endif
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

                <script type="text/javascript">
                    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);

                    $('#next-button').on('click', function() {
                        $('#install-loading').html('<span class="install-loading-bar"><span class="install-loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
                        $('.install-loading-bar').css({"height": $('#install-form').height() - 12});
                    });
                </script>
            </div>
        </div>
    </body>
</html>
