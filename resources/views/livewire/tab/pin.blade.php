<button class="flex items-center justify-center ltr:ml-2 rtl:mr-2 rounded-xl text-purple text-sm font-medium leading-6"
    data-tooltip-target="{{ $id }}-pin"
    data-tooltip-placement="top"
>
    <span
        id="{{ $pinned ? 'index-line-actions-unpin-tab-' . $id : 'index-line-actions-pin-tab-' . $id }}"
        @class([
            'text-sm transform rotate-45 transition-all',
            'material-icons-outlined hover:scale-125' => ($pinned) ? false : true,
            'material-icons' => (! $pinned) ? false : true,
        ])
        wire:click="changeStatus('{{ $tab }}')"
    >push_pin
    </span>

    @if ($pinned)
        <div 
            id="{{ $id }}-pin"
            role="tooltip"
            class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0"
        >
            {{ trans('general.pin_text.unpin_tab') }}
            <div class="absolute w-2 h-2 before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border -bottom-1 before:border-t-0 before:border-l-0 border-gray-200" data-popper-arrow></div>
        </div>
     @else
        <div 
            id="{{ $id }}-pin"
            role="tooltip"
            class=" inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0"
        >
            {{ trans('general.pin_text.pin_tab') }}
            <div class="absolute w-2 h-2 w-auto before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border -bottom-1 before:border-t-0 before:border-l-0 border-gray-200" data-popper-arrow></div>
        </div>
    @endif
</button>
