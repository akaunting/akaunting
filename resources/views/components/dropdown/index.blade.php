<div class="relative">
    <button type="button"
        @if ($trigger->attributes->has('override') && in_array('class', explode(',', $trigger->attributes->get('override'))))
        class="{{ $trigger->attributes->get('class') }}"
        @else
        class="w-full lg:w-9 h-9 flex items-center justify-center px-2 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-sm font-medium leading-6" 
        @endif
        data-dropdown-toggle="{{ $id }}"
        {{  $trigger->attributes }}
    >
        {!! $trigger !!}
    </button>

    <div
        id="{{ $id }}"
        class="absolute right-0 mt-3 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 hidden"
        @if ($attributes->has('style'))
        style="{{ $attributes->get('style') }}"
        @else
        style="left: auto; min-width: 10rem;"
        @endif
    >
        @stack('button_dropdown_start')

        {{ $slot }}
    </div>
</div>
