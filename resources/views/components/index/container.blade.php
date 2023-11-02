<x-loading.content />

<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'my-5']) : $attributes }}>
    {!! $slot !!}
</div>
