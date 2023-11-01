<div class="relative lg:w-8/12 ltr:lg:pl-8 rtl:lg:pr-8">
    @if (! isset($attributes['disable-loading']))
        <x-loading.absolute />
    @endif

    {!! $slot !!}
</div>
