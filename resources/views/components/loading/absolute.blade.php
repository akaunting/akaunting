<div
  x-data="{ }"
  x-init="document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.add('invisible') }) : null, setTimeout(() => $refs.loadingAbsoluteContent.remove(), 1000), setTimeout(() => document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.remove('invisible') }) : null , 1010)"
  x-ref="loadingAbsoluteContent"
  class="absolute w-full lg:flex items-start justify-center bg-body top-0 bottom-0 left-0 right-0 z-50"
  style="z-index: 60;"
>
  <img src="{{ asset('public/img/akaunting-loading.gif') }}" class="w-28 h-28" alt="Akaunting" />
</div>
<!--data attr added because for none vue.js pages-->