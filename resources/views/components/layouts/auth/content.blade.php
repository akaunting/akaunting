@stack('content_start')
<div class="w-full lg:w-46 h-31 flex flex-col justify-center gap-12 px-6 lg:px-24 py-24 mt-12 lg:mt-0">
    <div class="flex flex-col gap-4">
        {!! $slot !!}
    </div>
</div>
@stack('content_end')
