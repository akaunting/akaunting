@stack('content_start')
    @stack('content_header_start')

        <h1 class="text-white">
            @yield('title')
            @yield('new_button')
        </h1>

    @stack('content_header_end')

    @stack('content_content_start')

        @yield('content')

    @stack('content_content_end')

    <notifications></notifications>
@stack('content_end')
