@stack('content_start')
    <div id="app">
        @stack('content_content_start')

            @yield('content')

        @stack('content_content_end')
        <notifications></notifications>

        <akaunting-modal
            v-if="addNew.modal"
            :show="addNew.modal"
            :title="addNew.title"
            :message="addNew.html">
        </akaunting-modal>
    </div>
@stack('content_end')
