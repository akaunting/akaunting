<html lang="{{ app()->getLocale() }}">
    @include('partials.wizard.head')

    <body class="wizard-page">

        <div class="container mt--5">
            @stack('body_start')

            <div id="app">

                @include('partials.wizard.content')

            </div>

            @stack('body_end')
        </div>

        @include('partials.wizard.scripts')
    </body>

</html>
