@php
    $slot_text_length = strlen($slot);

    $slot_isHtml = strlen(strip_tags($slot)) < strlen($slot);
@endphp

@if ($slot_text_length >= '30' && ! $slot_isHtml)
<div data-truncate-parent class="flex lg:block truncate">
    <div
    @class([ 
        $width,
        'truncate',
    ])
    data-truncate
    >
        {!! $slot !!}
    </div>
</div>
@else
    {!! $slot !!}
@endif
