@php
    $slot_isHtml = strlen(strip_tags($slot)) < strlen($slot);

    $slot_is_string = strval(strlen($slot));
@endphp

<div>
    <div data-title-truncate>
        {!! $slot !!}
    </div>
</div>
