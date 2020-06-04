@stack('content_start')
    <div id="app">
        @stack('content_content_start')

            @yield('content')

        @stack('content_content_end')
        <notifications></notifications>

        <form id="form-create" method="POST" action="#"/>
        <component v-bind:is="component"></component>
    </div>
@stack('content_end')
