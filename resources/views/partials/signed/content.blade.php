@stack('content_start')
    <div id="app">
        @stack('content_header_start')
            <div class="row">
                <div class="col-md-6">
                    <h1>
                        @yield('title')
                    </h1>
                </div>
                <div class="col-md-6 text-right">
                    @yield('new_button')
                </div>
            </div>
        @stack('content_header_end')

        @stack('content_content_start')

            @yield('content')

        @stack('content_content_end')

        <notifications></notifications>

        <form id="form-dynamic-component" method="POST" action="#"></form>

        <component v-bind:is="component"></component>
    </div>
@stack('content_end')
