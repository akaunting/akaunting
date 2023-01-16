<div {{ ((! $attributes->has('override')) || ($attributes->has('override') && ! in_array('class', explode(',', $attributes->get('override'))))) ? $attributes->merge(['class' => 'border-b pb-4']) : $attributes }}
    x-data="{ {{ $type }} : {{ ($open) ? "'open'" : "'close'" }} }"
>
    @if (! empty($head) && $head->isNotEmpty())
        <div class="relative w-full text-left cursor-pointer group" x-on:click="{{ $type }} !== 'open' ? {{ $type }} = 'open' : {{ $type }} = 'close'">
            {!! $head !!}

            <x-icon filled class="absolute ltr:right-0 rtl:left-0 top-0 transition-all transform" :icon="$icon" x-bind:class="{{ $type }} === 'open' ? 'rotate-180' : ''" />
        </div>
    @endif

    @if (! empty($body) && $body->isNotEmpty())
        <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
            x-ref="accordion_{{ $type }}"
            x-bind:class="{{ $type }} == 'open' ? 'h-auto ' : 'scale-y-0 h-0'"
        >
            <div class="my-3">
                {!! $body !!}
            </div>
        </div>
    @endif

    @if (! empty($foot) && $foot->isNotEmpty())
        !! $foot !!}
    @endif
</div>
