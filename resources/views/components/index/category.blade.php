<div class="flex items-center">
    <span @class([
            'w-3 h-3 rounded-full ltr:mr-1 rtl:ml-1', $backgroundColor, $textColor
        ])
        @if (! empty($backgroundStyle))
        style="background-color: {{ $backgroundStyle }}"
        @endif
    >
    </span>
    <span class="w-24 truncate">{{ $name }}</span>
</div>
