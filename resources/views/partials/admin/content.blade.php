@stack('content_start')
    <div id="app">
        @stack('content_content_start')

            @yield('content')

        @stack('content_content_end')
        <notifications></notifications>
    </div>
@stack('content_end')
