<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'flex flex-col lg:flex-row sm:mt-12']) : $attributes }}>
    {!! $slot !!}
</div>
