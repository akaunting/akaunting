@props(['color', 'text'])

@php
    if (empty($color)) {
        $color = 'to-purple';
    }
@endphp

<span class="bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent {{ $color }} transition-backgroundSize">   
    {{ $text }} 
</span>