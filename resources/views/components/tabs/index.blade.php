@props(['active', 'id'])

@php
    if (! empty($attributes['slides'])) {
        $slides = $attributes['slides'];
    }

    if (empty($attributes['slides'])) {
        $slides = '2';
    }
@endphp

<div id="{{ $id }}" data-swiper="{{ $slides }}" class="swiper" x-data="{ active: window.location.hash.split('#')[1] == undefined ? '{{ $active }}' : window.location.hash.split('#')[1] }">
    <div class="swiper-tabs-container">
        <ul class="swiper-wrapper" {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'flex items-center']) : $attributes }}>
            {!! $navs !!}
        </ul>

        <div class="swiper-button-next top-3 right-0">
            <span class="material-icons">chevron_right</span>
        </div>

        <div class="swiper-button-prev top-3 left-0">
            <span class="material-icons">chevron_left</span>
        </div>
    </div>

    {!! $content !!}
</div>
