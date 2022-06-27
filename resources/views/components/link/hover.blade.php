@props(['color', 'text', 'groupHover'])

@php
    if (empty($color)) {
        $color = 'to-black';
    }

    if (empty($groupHover)) {
        $groupHover = false;
    }
@endphp

<span class="bg-no-repeat bg-0-2 bg-0-full {{ $groupHover ? 'group-hover:bg-full-2' : 'hover:bg-full-2' }} bg-gradient-to-b from-transparent {{ $color }} transition-backgroundSize">   
  {!! $slot !!}
</span>