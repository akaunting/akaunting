<div @class([
        'relative lg:h-60',
        $backgroundColor,
        'rounded-lg flex flex-col lg:flex-row items-center justify-between mt-8 mb-12',
    ])
>
    <div class="hidden lg:block px-4">
        <img src="{{ $image }}" class="w-60 h-60 object-contain mt-10 m-auto" alt="{{ $title ?? trans('general.no_records') }}" />
    </div>

    <div @class([
            'lg:w-3/5',
            $textColor,
            'ltr:text-right rtl:text-left py-4 lg:py-0 px-4 space-y-2',
        ])
    >
        <p class="mb-5">
            {!! $description !!}
        </p>

        @if (! empty($button) && $button->isNotEmpty())
            {!! $button !!}
        @else
            <x-link href="{!! $url !!}" class="bg-transparent" override="class">
                <x-link.hover color="to-white">
                    {{ $textAction }}
                </x-link.hover>
            </x-link>
        @endif
    </div>
</div>
