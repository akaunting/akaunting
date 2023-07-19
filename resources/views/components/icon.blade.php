@if ($simpleIcons)
    <div {{ $attributes->merge(['class' => $class]) }}>
        {!! simple_icons($icon) !!}
    </div>
@elseif ($custom)
    <div {{ $attributes->merge(['class' => $class]) }}>
        {!! file_get_contents($icon) !!}
    </div>
@else
    <span
        @class([
            'material-icons-outlined' => (! $filled && ! $rounded && ! $sharp),
            $class,
            'material-icons' => $filled,
            'material-icons-round' => $rounded,
            'material-icons-sharp'=> $sharp,
        ])
        {{ $attributes }}
    >{{ $icon }}</span>
@endif
