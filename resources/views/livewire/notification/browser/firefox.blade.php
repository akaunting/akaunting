<div>
    <div
        data-modal-handle
        @class([
            'firefox-confirm-modal modal w-full h-full fixed top-0 left-0 right-0 z-50 overflow-y-auto overflow-hidden fade justify-center items-start',
            'show flex flex-wrap modal-background' => ! $status,
            'hidden' => $status,
        ])
    >
        <div class="w-full my-10 m-auto flex flex-col px-2 sm:px-0 max-w-2xl">
            <div class="modal-content">
                <div class="p-5 bg-body rounded-tl-lg rounded-tr-lg">
                    <div class="flex items-center justify-between border-b pb-5">
                        <h4 class="text-base font-medium">
                            {!! trans('notifications.browser.firefox.title') !!}
                        </h4>
                    </div>
                </div>

                <div class="pt-1 pb-5 px-5 bg-body">
                    <div>
                        {!! trans('notifications.browser.firefox.description') !!}
                    </div>
                </div>

                <div class="p-5 bg-body rounded-bl-lg rounded-br-lg border-gray-300">
                    <div class="flex items-center justify-end space-x-2">
                        <button onclick="closeConfirmFirefox()" class="relative px-6 py-1.5 hover:bg-gray-100 rounded-lg disabled:bg-green-100">
                            {{ trans('general.cancel') }}
                        </button>

                        <button onclick="closeConfirmFirefox()" class="relative px-6 py-1.5 bg-green hover:bg-green-700 text-white rounded-lg disabled:bg-green-100">
                            {{ trans('general.confirm') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts_start')
<script>
    function closeConfirmFirefox() {
        const d = new Date();
        d.setTime(d.getTime() + (86400 * 90 * 90 * 90 * 30));

        let expires = "expires="+ d.toUTCString();

        document.cookie = "firefox-icon-notification-confirm=true;" + expires + ";path=/";

        document.querySelector('.firefox-confirm-modal').remove();
    }
</script>
@endpush
