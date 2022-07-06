<label class="text-black text-sm font-medium" {{ $attributes }}>
    {!! $slot !!}

    @if ($attributes->has('required') && $attributes->get('required') === true)
        <span class="text-red ltr:ml-1 rtl:mr-1">*</span>
    @endif
</label>