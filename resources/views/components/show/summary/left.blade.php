<div class="w-full lg:w-5/12 flex items-center">
    @if (! empty($avatar) && $avatar->isNotEmpty())
    <div class="w-12 h-12 flex items-center justify-center text-2xl font-regular border border-gray-300 rounded-full p-6">
        {!! $avatar !!}
    </div>
    @endif

    <div class="flex flex-col text-black text-sm font-medium ltr:ml-2 rtl:mr-2 sm:ltr:ml-8 sm:rtl:mr-8">
        {!! $slot !!}
    </div>
</div>
