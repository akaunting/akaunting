<span
    @class([
        'relative',
        $width,
    ])
    data-tooltip-target="{{ $id }}"
    data-tooltip-placement="{{ $placement }}"
>
    {!! $slot !!}
</span>

<div id="{{ $id }}"
    role="tooltip"
    @class([
        'inline-block absolute invisible z-20 py-1 px-2',
        'text-sm font-medium',
        'rounded-lg',
        $backgroundColor,
        $textColor,
        $size,
        'border',
        $borderColor,
        'shadow-sm opacity-0',
        $whitespace
    ])
>
    {!! $message !!}

    <div 
        @class([
            'absolute w-2 h-2 before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border',
            $tooltipPosition,
            $borderColor
        ])
        data-popper-arrow
    >
    </div>
</div>
