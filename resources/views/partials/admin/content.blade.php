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

            <template #card-footer>
                <div class="float-right">
                    <button type="button" class="btn btn-outline-secondary" @click="onCancelNewItem()">
                        <span>{{ trans('general.cancel') }}</span>
                    </button>
    
                    <button type="button" class="btn btn-success button-submit" @click="onNewItemSubmit()">
                        <div class="aka-loader d-none"></div>
                        <span>{{ trans('general.confirm') }}</span>
                    </button>
                </div>
            </template>
    
        </akaunting-modal>
    </div>
@stack('content_end')
