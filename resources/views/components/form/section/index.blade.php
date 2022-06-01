<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'mb-14']) : $attributes }}>
    @if (!empty($head) && $head->isNotEmpty())
        {!! $head !!}
    @endif

    @if (! empty($body) && $body->isNotEmpty())
    <div class="grid sm:grid-cols-6 gap-x-8 gap-y-6 my-3.5">
        {!! $body !!}
    </div>
    @endif

    @if (! empty($foot) && $foot->isNotEmpty())
    <div class="relative__footer">
        {!! $foot !!}
    </div>
    @endif
</div>
