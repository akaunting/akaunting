<div data-loading-menu class="w-70 h-screen hidden lg:flex fixed top-0 js-menu z-20 transition-all ltr:-left-80 rtl:-right-80 xl:ltr:left-0 xl:rtl:right-0">
    <div class="w-14 py-7 px-1 bg-lilac-900 z-10 menu-scroll overflow-y-auto overflow-x-hidden">
        <div class="flex flex-col items-center justify-center mb-7 cursor-pointer">
            <span class="w-7 h-7 flex items-center justify-center text-2xl">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </span>
        </div>

        <div class="group flex flex-col items-center justify-center">
            <div class="items-center menu-button justify-center w-6 h-6 mb-5 relative cursor-pointer js-menu-toggles">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>

            <div class="flex items-center justify-center w-6 h-6 mb-5 cursor-pointer">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>

            <div class="flex items-center justify-center w-6 h-6 mb-5 cursor-pointer">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>

            <div class="flex items-center justify-center w-6 h-6 mb-5 cursor-pointer"></div>

            <div class="flex items-center justify-center w-6 h-6 mb-5 cursor-pointer">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>

            <div class="flex items-center justify-center w-6 h-6 mb-5 cursor-pointer">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </div>

    <nav class="menu-list">
        <div class="relative flex items-center mb-7">
            <div class="w-7 h-7 flex items-center justify-center">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>

            <div class="w-8/12 h-3 ltr:pl-3 rtl:pr-3">
                <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
            </div>
        </div>

        <div class="transform">
            <div class="relative flex items-center mb-5">
                <div class="w-6 h-6 flex items-center justify-center">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>

                <div class="w-9/12 h-3 ltr:pl-3 rtl:pr-3">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>
            </div>
            <div class="relative flex items-center mb-5">
                <div class="w-6 h-6 flex items-center justify-center">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>

                <div class="w-7/12 h-3 ltr:pl-3 rtl:pr-3">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>
            </div>
            <div class="relative flex items-center mb-5">
                <div class="w-6 h-6 flex items-center justify-center">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>

                <div class="w-10/12 h-3 ltr:pl-3 rtl:pr-3">
                    <div class="w-full h-full animate-pulse bg-gray-300 rounded-full"></div>
                </div>
            </div>
        </div>
    </nav>
</div>

@push('scripts_start')
    <script type="text/javascript">
        let loading_menu_html = document.querySelectorAll('[data-loading-menu]');
        let loading_html = document.querySelectorAll('[data-loading-content]');
        let loading_absolute_html = document.querySelectorAll('[data-absolute-loading-content]');
        let menu_html = document.querySelector('[data-real-menu]');
        let timer = 1000;

        window.addEventListener('DOMContentLoaded', (event) => {
            setTimeout(() => {
                loading_menu_html[0].remove();
                menu_html.classList.remove('hidden');
            }, 800);

            if (loading_html.length) {
                setTimeout(() => {
                    loading_html[0].remove();
                }, timer);
            }

            if (loading_absolute_html.length) {
                setTimeout(() => {
                    loading_absolute_html[0].remove();
                }, timer);
            }
        });
    </script>
@endpush
