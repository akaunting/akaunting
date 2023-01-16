<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'mb-14']) : $attributes }}>
    @if (!empty($head) && $head->isNotEmpty())
        {!! $head !!}
    @endif

    @if (! empty($body) && $body->isNotEmpty())
        <div
            @class([
                'grid my-3.5',
                $spacingVertical,
                $spacingHorizontal,
                $columnNumber,
            ])
        >
            {!! $body !!}
        </div>
    @endif

    @if (! empty($foot) && $foot->isNotEmpty())
        {!! $foot !!}
    @endif
</div>
