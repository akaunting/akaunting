<html lang="{{ app()->getLocale() }}">
    @include('partials.wizard.head')

    <body class="wizard-page">

        <div class="container mt--5">
            @stack('body_start')

            <div id="app">
                <div class="card-body">

                    @include('flash::message')

                    @yield('content')
                </div>
            </div>

        </div>

        @include('partials.wizard.scripts')

    </body>
</html>