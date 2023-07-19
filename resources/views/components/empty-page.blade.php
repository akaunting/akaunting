<div class="flex flex-col lg:flex-row">
    <div class="w-full lg:w-1/2">
        <div class="border-b px-2 pb-3">
            <h1 class="flex items-center text-2xl xl:text-5xl text-black font-light -ml-0.5">
                <x-title>
                    {!! $title !!}
                </x-title>
            </h1>

            <p class="mt-6 text-sm">
                {!! $description !!}
            </p>
        </div>

        <ul>
            @foreach ($buttons as $button)
                @if ($checkPermissionCreate)
                @can ($button['permission'])
                @endif
                    <li class="border-b p-2 hover:bg-gray-100">
                        <x-link href="{{ $button['url']}}" class="flex items-center justify-between text-xs" override="class">
                            <div class="truncate">
                                <div class="flex items-center">
                                    <h2 class="relative">
                                        {{ $button['text'] }}

                                        @if (! empty($button['active_badge']))
                                            <div class="absolute w-7 h-7 opacity-100 z-10 -top-1.5 -right-8">
                                                <div class="absolute w-5 h-5 left-0 top-1 border border-gray-100 rounded-full animate-pulsate_transparent bg-white"></div>
                                                <div class="absolute w-2 h-2 top-2.5 left-1.5 rounded-full bg-green-400"></div>
                                            </div>
                                        @endif
                                    </h2>
                                </div>

                                <div class="h-4 overflow-hidden text-black-400 truncate">{{ $button['description'] }}</div>
                            </div>

                            <span class="material-icons text-gray-500 transform rtl:rotate-180">chevron_right</span>
                        </x-link>
                    </li>
                @if ($checkPermissionCreate)
                @endcan
                @endif
            @endforeach

            @if (! empty($suggestions))
                @foreach ($suggestions as $suggestion) 
                    <li class="border-b p-2  hover:bg-gray-100">
                        <x-link href="{{ url($suggestion->action_url) . '?' . http_build_query((array) $suggestion->action_parameters) }}" class="flex items-center justify-between text-xs" override="class">
                            <div class="truncate">
                                <h2>
                                    {{ $suggestion->name }}
                                </h2>

                                <div class="h-4 overflow-hidden text-black-400 truncate">
                                    {{ $suggestion->description ?? '' }}
                                </div>
                            </div>

                            <span class="material-icons text-gray-500 rtl:rotate-180">chevron_right</span>
                        </x-link>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>

    <div class="w-full lg:w-1/2 flex justify-end mt-8 lg:mt-60">
        <img src="{{ $image }}" alt="{{ $title }}" class="ltr:scale-x-100 rtl:-scale-x-100" />
    </div>
</div>

@push('css')
    <style>
        .hide-empty-page {
            display: none;
        }
    </style>
@endpush

