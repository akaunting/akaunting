<div
    x-data="{ loaded: false }"
    x-init="() => {
        const loadEvent = 'onpagehide' in window ? 'pageshow' : 'load';

        window.addEventListener(loadEvent, () => {
            loaded = true;
            $refs.loadingAbsoluteContent.remove();
            document.querySelectorAll('[data-modal-handle]').forEach(item => item.classList.remove('invisible'));
        });
    }"
    x-ref="loadingAbsoluteContent"
    class="absolute w-full lg:flex items-start justify-center bg-body top-0 bottom-0 left-0 right-0 z-50"
    style="z-index: 60;"
>
    <img src="{{ asset('public/img/akaunting-loading.gif') }}" class="w-40 h-40" alt="Akaunting" />
</div>
<!--data attr added because for none vue.js pages-->
