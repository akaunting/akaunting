<td class="{{ $class }}" {{ $attributes }}>
    @if (!empty($first))
    @php
        $first_attributes = $first->attributes;

        if ((! $first->attributes->has('override')) || ($first->attributes->has('override') && ! in_array('class', explode(',', $first->attributes->get('override'))))) {
            $first_attributes = $first->attributes->merge(['class' => 'font-medium truncate']);
        }
    @endphp
    <div {{ $first_attributes }}>
        <!-- first div for restrict of width. With javascript will add (overflow-x-hidden) class name -->
        <div>
            <!-- There needs to be two div for disable/enable icons. If I don't create this div, animation will work with disable/enable icons -->
            <div>
                <!-- this tag use for calculate width of text and parent element -->
                <span data-truncate>
                    {!! $first !!}
                </span>
            </div>
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
            <div>
                <span data-truncate>
                    {!! $second !!}
                </span>
            </div>
        </div>
    </div>
    @endif

    <div>
        <div>
            <span data-truncate>
                {{ $slot }}
            </span>
        </div>
    </div>
</td>
