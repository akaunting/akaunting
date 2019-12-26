<html lang="{{ app()->getLocale() }}">
    @include('partials.signed.head')

    <body>
        @stack('body_start')

        <div class="container-fluid content-layout mt-4">

            @include('partials.signed.content')

            @include('partials.signed.footer')

        </div>

        @stack('body_end')
    </body>

</html>
