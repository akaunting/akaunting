<div
    x-data="{ loaded: false }"
    x-init="() => {
        const loadEvent = 'onpagehide' in window ? 'pageshow' : 'load';

        window.addEventListener(loadEvent, () => {
            loaded = true; 
            $refs.loadingMenu.remove();
        });
    }"
    x-ref="loadingMenu"
    class="w-70 h-screen hidden lg:flex fixed top-0 z-20 transition-all ltr:-left-80 rtl:-right-80 xl:ltr:left-0 xl:rtl:right-0"
>
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
