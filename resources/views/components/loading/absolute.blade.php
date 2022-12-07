<div
  x-data="{ }"
  x-init="document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.add('invisible') }) : null, setTimeout(() => $refs.loadingAbsoluteContent.remove(), 1000), setTimeout(() => document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.remove('invisible') }) : null , 1010)"
  x-ref="loadingAbsoluteContent"
  class="absolute-modal"
>
  <img src="{{ asset('public/img/akaunting-loading.gif') }}" alt="Akaunting" />
</div>
<!--data attr added because for none vue.js pages-->