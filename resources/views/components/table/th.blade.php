<th scope="col" class="{{ $class }}" {{ $attributes }}>
    @if (!empty($first))
    @php
        $first_attributes = $first->attributes;

        if ((! $first->attributes->has('override')) || ($first->attributes->has('override') && ! in_array('class', explode(',', $first->attributes->get('override'))))) {
            $first_attributes = $first->attributes->merge(['class' => 'font-medium truncate']);
        }
    @endphp
    <div {{ $first_attributes }}>
        <!--so that the animation does not overflow the width. With javascript will add (overflow-x-hidden) class name-->
        <div>
            <!-- this tag use for calculate width of text and parent element -->
            <span data-truncate-marquee>
                {!! $first !!}
            </span>
        </div>
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
        <div>
            <span data-truncate-marquee>
                {!! $second !!}
            </span>
        </div>
    </div>
    @endif

    <div>
        <span data-truncate-marquee>
            {{ $slot }}
        </span>
    </div>
</th>
