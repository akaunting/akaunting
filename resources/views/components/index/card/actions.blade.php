<x-dropdown id="{{ $id }}">
    <x-slot name="trigger" class="flex items-center justify-center w-9 h-9 rounded-xl hover:bg-gray-200 text-purple" override="class">
        <span class="material-icons-outlined pointer-events-none">more_horiz</span>
    </x-slot>

    @php
        $divider = false;

        $types = array_map(fn ($action) => ! empty($action['type']) ? $action['type'] : 'link', $actions);

        $first = array_flip(array_reverse($types, true));
        $last = array_flip($types);
    @endphp

    @foreach ($actions as $key => $action)
        @php($type = $types[$key])

        @if ($type === 'divider')
            {{-- Drawn only where something came before it, so a hidden action does not leave
                 a line with nothing on either side of it --}}
            @if ($divider)
                <x-dropdown.divider />

                @php($divider = false)
            @endif

            @continue
        @endif

        @if ($first[$type] === $key)
            @stack($stack . '_' . $type . '_start')
        @endif

        @if (empty($action['permission']) || user()->can($action['permission']))
            @php($divider = true)

            @switch($type)
                @case('delete')
                    <x-delete-link :model="$action['model']" :route="$action['route']" />
                    @break

                @case('button')
                    @php($action_id = $action['attributes']['id'] ?? '')

                    <div class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap" {!! $action['attributes'] ?? null !!}>
                        <button type="button" class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">
                            {{ $action['title'] }}
                        </button>
                    </div>
                    @break

                @default
                    @php($action_id = $action['attributes']['id'] ?? '')

                    <x-dropdown.link href="{{ $action['url'] }}" id="{{ $action_id }}">
                        {{ $action['title'] }}
                    </x-dropdown.link>
            @endswitch
        @endif

        @if ($last[$type] === $key)
            @stack($stack . '_' . $type . '_end')
        @endif
    @endforeach

    @stack($stack)
</x-dropdown>
