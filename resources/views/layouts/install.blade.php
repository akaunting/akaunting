<html>
    @include('partials.install.head')

    <body class="installation-page">

        <div class="main-content">
            <div class="header pt-3 pb-2">
                <div class="container">
                    <div class="header-body text-center mb-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-5">
                                <img class="pb-6" src="{{ asset('public/img/akaunting-logo-white.svg') }}" width="22%" alt="Akaunting"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt--7 pb-5">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-7">
                        <div id="app">
                            {!! Form::open([
                                'url' => url()->current(),
                                'role' => 'form',
                                'id' => 'form-install',
                                '@submit.prevent' => 'onSubmit',
                                '@keydown' => 'form.errors.clear($event.target.name)',
                            ]) !!}
                            <div class="card-body">
                                <div class="text-center text-muted mt-2 mb-4">
                                    <small>@yield('header')</small>
                                </div>

                                @include('flash::message')

                                @yield('content')
                            </div>

                            <div class="card-footer">
                                <div class="float-right">
                                    @if (Request::is('install/requirements'))
                                        <a href="{{ route('install.requirements') }}" class="btn btn-success"> {{ trans('install.refresh') }} &nbsp;<i class="fa fa-refresh"></i></a>
                                    @else
                                        {!! Form::button(
                                            '<i v-if="loading" :class="(loading) ? \'show \' : \'\'" class="fas fa-spinner fa-spin d-none"></i> ' .
                                            trans('install.next') .
                                            ' &nbsp;<i class="fa fa-arrow-right"></i>',
                                            [
                                                ':disabled' => 'loading',
                                                'type' => 'submit',
                                                'id' => 'next-button',
                                                'class' => 'btn btn-success',
                                                'data-loading-text' => trans('general.loading')
                                            ]
                                        ) !!}
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.install.scripts')

    </body>

</html>
