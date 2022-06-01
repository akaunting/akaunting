<button class="ltr:mr-4 rtl:m-4" data-tooltip-target="{{ $reportId }}-pin" data-tooltip-placement="bottom">
    <span
        id="{{ $pinned ? 'reports-unpin-' . $reportId : 'reports-pin-' . $reportId }}"
        @class([
            'text-black-400 text-lg transform rotate-45 cursor-pointer mx-2',
            'material-icons-outlined' => ($pinned) ? false : true,
            'material-icons' => (! $pinned) ? false : true,
        ])
        wire:click="changeStatus('{{ $reportId }}')"
    >push_pin
    </span>

    @if ($pinned)
        <div id="{{ $reportId }}-pin" role="tooltip" class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 tooltip-content">
            {{ trans('reports.pin_text.unpin_report') }}
            <div class="absolute w-2 h-2 -top-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
        </div>
     @else
        <div id="{{ $reportId }}-pin" role="tooltip" class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0 tooltip-content">
            {{ trans('reports.pin_text.pin_report') }}
            <div class="absolute w-2 h-2 -top-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
        </div>
    @endif
</button>
