@foreach ($tips as $item)
    <div class="relative hidden lg:block w-4/12 mt-12 xl:mt-9.5 lg:pl-12">
        <div @class([
                'relative leading-4 z-10',
                'ltr:text-right rtl:text-left' => $item->align == 'right',
                'ltr::text-left rtl:text-right' => $item->align == 'left',
            ])
        >
            <h2 class="font-bold mb-1">
                {{ $item->title }}
            </h2>

            <div
                @class([
                    'float-right' => $item->align == 'right',
                    'float-left' => $item->align == 'left',
                ])

                style="width: 200px;"
            >
                <div class="text-sm mb-2">
                    {!! $item->description !!}
                </div>

                <x-link href="{{ $item->action }}" class="font-light text-sm" override="class" target="_blank">
                    <x-link.hover>
                        {{ $item->learn_more }}
                    </x-link.hover>
                </x-link>
            </div>
        </div>

        <div class="absolute ltr:right-0 rtl:left-0">
            <img src="{{ $item->thumb }}" alt="{{ $item->title }}" />
        </div>
    </div>
@endforeach
