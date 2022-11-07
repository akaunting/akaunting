@php $reportClassForId = Str::slug(Str::snake(class_basename($report->class))); @endphp

<button class="w-8 h-8 flex items-center justify-center px-2 py-2 rounded-xl text-purple text-sm font-medium leading-6" data-tooltip-target="{{ $report->id }}-pin" data-tooltip-placement="bottom">
    <span
        id="{{ $pinned ? 'index-line-actions-unpin-report-' . $reportClassForId . '-' . $report->id : 'index-line-actions-pin-report-' . $reportClassForId . '-' . $report->id }}"
        @class([
            'text-lg transform rotate-45 transition-all',
            'material-icons-outlined hover:scale-125' => ($pinned) ? false : true,
            'material-icons' => (! $pinned) ? false : true,
        ])
        wire:click="changeStatus('{{ $report->id }}')"
    >push_pin
    </span>

    @if ($pinned)
        <div id="{{ $report->id }}-pin" role="tooltip" class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0">
            {{ trans('reports.pin_text.unpin_report') }}
            <div class="absolute w-2 h-2 -top-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
        </div>
     @else
        <div id="{{ $report->id }}-pin" role="tooltip" class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm whitespace-nowrap opacity-0">
            {{ trans('reports.pin_text.pin_report') }}
            <div class="absolute w-2 h-2 -top-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
        </div>
    @endif
</button>
