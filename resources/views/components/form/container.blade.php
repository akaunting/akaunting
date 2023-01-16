<div class="flex flex-col lg:flex-row">
    <div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'relative lg:w-8/12 z-10']) : $attributes }}>
        @if (! empty($head) && $head->isNotEmpty())
            {!! $head !!}
        @endif

        <div class="relative mt-4">
            {!! $slot !!}
        </div>

        @if (!empty($foot) && $foot->isNotEmpty())
            {!! $foot !!}
        @endif
    </div>

    <x-tips position="relative" />
</div>

<x-tips position="fixed" />
