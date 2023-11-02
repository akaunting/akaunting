<span
    @class([
        $color,
        $groupHover ? 'group-hover:bg-full-2' : 'hover:bg-full-2',
        'bg-no-repeat bg-0-2 bg-0-full bg-gradient-to-b from-transparent transition-backgroundSize cursor-pointer'
    ])
>
    {!! $slot !!}
</span>
