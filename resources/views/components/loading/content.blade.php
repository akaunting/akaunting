<div
    x-data="{ loaded: false }"
    x-init="() => {
        const loadEvent = 'onpagehide' in window ? 'pageshow' : 'load';

        window.addEventListener(loadEvent, () => {
            loaded = true;
            $refs.loadingContent.remove();
            document.querySelectorAll('[data-modal-handle]').forEach(item => item.classList.remove('invisible'));
        });
    }"
    x-ref="loadingContent"
    class="fixed w-full lg:w-4/5 h-screen flex items-center justify-center bg-body top-0 bottom-0 ltr:right-0 rtl:left-0 -mx-1 z-50"
    style="z-index: 60;"
>
    <img src="{{ asset('public/img/akaunting-loading.gif') }}" class="w-40 h-40 lg:-mt-16" alt="Akaunting" />
</div>
<!--data attr added because for none vue.js pages-->
