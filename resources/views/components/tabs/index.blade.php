@props(['active'])

@php
    if (! empty($attributes['slides'])) {
        $slides = $attributes['slides'];
    } else {
        $slides = '3';
    }

    if (! empty($attributes['is_slider'])) {
        $is_slider = true;
    } else {
        $is_slider = false;
    }
@endphp

<div data-swiper="{{ $slides }}" data-is-slider="{{ $is_slider }}" x-data="{ active: window.location.hash.split('#')[1] == undefined ? '{{ $active }}' : window.location.hash.split('#')[1] }">
    <div data-tabs-swiper>
        <ul data-tabs-swiper-wrapper {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'flex items-center']) : $attributes }}>
            {!! $navs !!}
        </ul>
    </div>

    {!! $content !!}
</div>
