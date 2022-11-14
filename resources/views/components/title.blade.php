@php
    $slot_isHtml = strlen(strip_tags($slot)) < strlen($slot);

    $slot_is_string = strval(strlen($slot));
@endphp

@if ($slot_is_string >= $textSize && ! $slot_isHtml)
<div class="overflow-x-hidden">
    <div data-title-truncate class="truncate">
        {!! $slot !!}
    </div>
</div>
@else 
    {!! $slot !!}
@endif