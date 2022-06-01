<div class="flex flex-col items-center justify-center">
    <div class="w-8 h-8 mb-2.5"></div>

    @foreach ($favorites as $favorite)
        <x-tooltip id="{{ $favorite['title'] }}" placement="right" message="{{ $favorite['title'] }}">
            <a href="{{ $favorite['url'] }}" class="w-8 h-8 flex items-center justify-center mb-2.5">
                <span
                    id="{{ $favorite['id'] }}"
                    @class([
                        'material-icons-outlined' => ! $favorite['active'],
                        'material-icons' => $favorite['active'],
                        'text-purple cursor-pointer',
                    ])
                >
                    {{ $favorite['icon'] }}
                </span>
            </a>
        </x-tooltip>
    @endforeach
</div>
