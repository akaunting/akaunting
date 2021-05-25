<html>
    @include('partials.wizard.head')

    <body class="wizard-page">

        <div class="container mt--5">
            @stack('body_start')

            <div id="app">
                <div class="card-body">
                    <div class="document-loading" v-if="!page_loaded">
                        <div>
                            <i class="fas fa-spinner fa-pulse fa-7x"></i>
                        </div>
                    </div>

                    @include('flash::message')

                    @yield('content')
                </div>
            </div>

        </div>

        @include('partials.wizard.scripts')

    </body>
</html>