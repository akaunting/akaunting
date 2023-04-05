@props(['active'])

@php
    if (! empty($attributes['slides'])) {
        $slides = $attributes['slides'];
    } else {
        $slides = null;
    }
@endphp

<div
    data-swiper="{{ $slides }}"
    @if(! $attributes->has('ignore-hash'))
        x-data="{ active: window.location.hash.split('#')[1] == undefined ? '{{ $active }}' : window.location.hash.split('#')[1] }"
    @else
        x-data="{ active: '{{ $active }}' }"
    @endif
>
    <div data-tabs-swiper>
        <ul data-tabs-swiper-wrapper {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'inline-flex overflow-x-scroll large-overflow-unset']) : $attributes }}>
            {!! $navs !!}
        </ul>
    </div>

    {!! $content !!}
</div>
