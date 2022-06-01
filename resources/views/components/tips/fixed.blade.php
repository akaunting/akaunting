@foreach ($tips as $item)
    <div class="fixed container top-36 xl:top-28 hidden lg:block">
        <div @class([
                'relative leading-4 z-10',
                'ltr:text-right rtl:text-left' => $item->align == 'right',
                'ltr::text-left rtl:text-right' => $item->align == 'left',
            ])
        >
            <h2 class="font-bold mb-1">
                {{ $item->title }}
            </h2>

            <p class="text-sm mb-2">
                {!! $item->description !!}
            </p>

            <a href="{{ $item->action }}" class="font-light text-sm border-b border-transparent transition-all hover:border-black" target="_blank">
                {{ $item->learn_more }}
            </a>
        </div>

        <div class="absolute ltr:right-0 rtl:left-0 -top-4">
            <img src="{{ $item->thumb }}" alt="{{ $item->title }}" />
        </div>
    </div>
@endforeach
