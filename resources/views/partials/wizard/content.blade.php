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

    <form id="form-dynamic-component" method="POST" action="#"></form>

    <component v-bind:is="component"></component>
@stack('content_end')
