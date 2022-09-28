@props(['active'])

<div x-data="{ active: window.location.hash.split('#')[1] == undefined ? '{{ $active }}' : window.location.hash.split('#')[1] }">
    <div>
        <ul {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'flex items-center']) : $attributes }}>
            {!! $navs !!}
        </ul>
    </div>

    {!! $content !!}
</div>
