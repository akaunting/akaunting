<div class="relative lg:w-8/12 lg:ltr:pl-8 lg:rtl:pr-8">
    @if (! isset($attributes['disable-loading']))
        <x-loading.absolute />
    @endif

    {!! $slot !!}
</div>
