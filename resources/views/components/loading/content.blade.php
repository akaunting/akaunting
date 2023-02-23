<div
  x-data="{ }"
  x-init="document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.add('invisible') }) : null, setTimeout(() => $refs.loadingContent.remove(), 1000), setTimeout(() => document.querySelector('[data-modal-handle]') ? document.querySelectorAll('[data-modal-handle]').forEach((item) => { item.classList.remove('invisible') }) : null , 1010)"
  x-ref="loadingContent"
  class="fixed w-full lg:w-4/5 h-screen flex items-center bg-body justify-center top-0 bottom-0 ltr:right-0 rtl:left-0 -mx-1 z-50"
  style="z-index: 60;"
>
<div class="lg:w-4/5 w-full">
  <Lottie />
</div>

</div>
<!--data attr added because for none vue.js pages-->
