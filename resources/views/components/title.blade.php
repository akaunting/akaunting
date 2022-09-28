@php
    $slot_isHtml = strlen(strip_tags($slot)) < strlen($slot);

    $slot_is_string = strval(strlen($slot));
@endphp

@if ($slot_is_string >= $textSize && ! $slot_isHtml)
    <x-tooltip id="page-title" placement="bottom" message="{!! $slot !!}">
        <div class="truncate" style="width: 22rem;">
            {!! $slot !!}
        </div>
    </x-tooltip>
@else 
    {!! $slot !!}
@endif