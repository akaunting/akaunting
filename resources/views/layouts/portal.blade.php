<html lang="{{ app()->getLocale() }}">

    @include('partials.portal.head')

    <body id="leftMenu" class="g-sidenav-show g-sidenav-pinned">
        @stack('body_start')

        @include('partials.portal.menu')

        <div class="main-content" id="panel">

            @include('partials.portal.navbar')

            <div id="main-body">

                @include('partials.portal.header')

                <div class="container-fluid content-layout mt--6">

                    @include('partials.portal.content')

                    @include('partials.portal.footer')

                </div>

            </div>

        </div>

        @stack('body_end')

        @include('partials.portal.scripts')
    </body>

</html>
