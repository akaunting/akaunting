@props(['action'])
@php
    if (empty($action)) {
        $action = 'form.loading';
    }
@endphp

<i
    @class([
        'animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto',
        'before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s]',
        'after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]'
    ])
    {{ $attributes }}
    v-if="{{ $action }}"
>
</i>

<span :class="[{'opacity-0': {{ $action }}}]">
    {!! $slot !!}
</span>
