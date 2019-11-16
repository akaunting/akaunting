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

    <akaunting-modal
        v-if="addNew.modal"
        :show="addNew.modal"
        :title="addNew.title"
        :message="addNew.html">
    </akaunting-modal>
@stack('content_end')
