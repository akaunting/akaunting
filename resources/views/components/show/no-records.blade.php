<div @class([
        'relative lg:h-60',
        $backgroundColor,
        'rounded-lg flex flex-col lg:flex-row items-center justify-between mt-8 mb-12',
    ])
>
    <div class="hidden lg:block px-4">
        <img src="{{ $image }}" class="w-60 h-60 object-contain mt-10 m-auto" />
    </div>


    <div @class([
            'lg:w-3/5',
            $textColor,
            'text-right py-4 lg:py-0 px-4 space-y-2',
        ])
    >
        <p class="mb-5">
            {!! $description !!}
        </p>

        @if (! empty($button) && $button->isNotEmpty())
            {!! $button !!}
        @else
            <a href="{!! $url !!}" class="border-b border-transparent transition-all hover:border-white">
                {{ $textAction }}
            </a>
        @endif
    </div>
</div>