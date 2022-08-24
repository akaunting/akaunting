@php
    $slot_isHtml = strlen(strip_tags($slot)) < strlen($slot);
@endphp

@if (strlen($slot) >= 25 && ! $slot_isHtml)
    <x-tooltip id="page-title" placement="bottom" message="{!! $slot !!}">
        <div class="truncate" style="width: 22rem;">
            {!! $slot !!}
        </div>
    </x-tooltip>
@else 
    {!! $slot !!}
@endif