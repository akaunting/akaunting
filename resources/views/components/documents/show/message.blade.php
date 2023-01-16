@stack($type . '_message_start')

<div @class([
        'rounded-xl px-5 py-3 mb-5',
        $backgroundColor
    ])
>
    <p @class([
            'text-sm mb-0',
            $textColor
        ])
    >
        {!! html_entity_decode($message) !!}
    </p>
</div>

@stack($type . '_message_end')