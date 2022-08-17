@props(['active'])

<div x-data="{ active: '{{ $active }}' }">
    <div>
        <ul {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'flex items-center']) : $attributes }}>
            {!! $navs !!}
        </ul>
    </div>

    {!! $content !!}
</div>
