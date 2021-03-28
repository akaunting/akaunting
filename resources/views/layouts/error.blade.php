<html lang="{{ app()->getLocale() }}">

    @include('partials.admin.head')

    <body id="leftMenu" class="g-sidenav-show g-sidenav-pinned">
        @stack('body_start')

        <div class="main-content" id="panel">

            <div id="main-body">

                @include('partials.admin.header')

                <div class="container-fluid content-layout mt--6">

                    @include('partials.admin.content')

                    @include('partials.admin.footer')

                </div>

            </div>

        </div>

        @stack('body_end')

        @include('partials.admin.scripts')
    </body>

</html>
