<div class="flex flex-col items-center justify-center">
    <div class="w-8 h-8 mb-2.5"></div>

    @foreach ($favorites as $favorite)
        <x-tooltip id="{{ $favorite['title'] }}" placement="right" message="{{ $favorite['title'] }}">
            <x-link href="{{ $favorite['url'] }}" class="w-8 h-8 flex items-center justify-center mb-2.5" override="class">
                <span
                    id="{{ $favorite['id'] }}"
                    @class([
                        'material-icons-outlined transform transition-all hover:scale-125' => ! $favorite['active'],
                        'material-icons' => $favorite['active'],
                        'text-purple cursor-pointer',
                    ])
                >
                    {{ $favorite['icon'] }}
                </span>
            </x-link>
        </x-tooltip>
    @endforeach
</div>
