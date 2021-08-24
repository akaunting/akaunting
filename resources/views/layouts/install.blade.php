<html>
    @include('partials.install.head')

    @stack('body_start')
    <body class="installation-page">
        <div class="main-content">
                <div class="container">
                    @stack('body_start')
                    <div id="app"></div>
                </div>
            </div>
        </div>
        @include('partials.install.scripts')
    </body>
</html>
