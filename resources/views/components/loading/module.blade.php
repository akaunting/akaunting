<div
  x-data="{ }"
  x-init="document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.add('invisible') }) : null, setTimeout(() => $refs.loadingModuleContent.remove(), 1000), setTimeout(() => document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.remove('invisible') }) : null , 1010)"
  x-ref="loadingModuleContent"
  class="absolute lg:flex items-center justify-center bg-body top-0 bottom-0 left-0 right-0"
  style="width: 120%; z-index: 60;"
>
  <img src="{{ asset('public/img/akaunting-loading.gif') }}" class="w-28 h-28" alt="Akaunting" />
</div>