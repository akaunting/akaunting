<td class="{{ $class }}" {{ $attributes }}>
    @if (!empty($first))
    @php
        $first_attributes = $first->attributes;

        if ((! $first->attributes->has('override')) || ($first->attributes->has('override') && ! in_array('class', explode(',', $first->attributes->get('override'))))) {
            $first_attributes = $first->attributes->merge(['class' => 'font-medium truncate']);
        }
    @endphp
    <div {{ $first_attributes }}>
        <x-marguee-text>
            {!! $first !!}
        </x-marguee-text>
    </div>
    @endif

    @if (!empty($second))
    @php
        $second_attributes = $second->attributes;

        if ((! $second->attributes->has('override')) || ($second->attributes->has('override') && ! in_array('class', explode(',', $second->attributes->get('override'))))) {
            $second_attributes = $second->attributes->merge(['class' => 'font-normal truncate']);
        }
    @endphp
    <div {{ $second_attributes }}>
        <x-marguee-text>
            {!! $second !!}
        </x-marguee-text>
    </div>
    @endif

    <x-marguee-text>
        {{ $slot }}
    </x-marguee-text>
</td>
