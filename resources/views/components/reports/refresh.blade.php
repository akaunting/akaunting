@props(['report'])

@if ($report->cached_at)
    @php
        // The raw query: load() has folded its keys into search, so getUrl() would repeat them
        $query = request()->getQueryString();

        // The language middleware can leave Carbon on the bare language code (en, not en_GB),
        // so the time (14:05, 2:05 PM) is formatted in the app locale
        $cached_at = $report->cached_at->copy()->locale(app()->getLocale());
    @endphp

    <div
        class="relative"
        x-data="{ refreshing: false }"
        x-on:pageshow.window="refreshing = false"
    >
        <x-link
            href="{{ route('reports.clear', $report->model->id) . ($query ? '?' . $query : '') }}"
            id="show-more-actions-refresh-report"
            aria-label="{{ trans('general.refresh') }}"
            aria-describedby="tooltip-refresh-report"
            x-on:click="refreshing ? $event.preventDefault() : refreshing = ! ($event.ctrlKey || $event.metaKey || $event.shiftKey || $event.altKey)"
            x-bind:class="{ 'pointer-events-none': refreshing }"
            override="class"
            class="w-full lg:w-9 h-9 flex items-center justify-center px-2 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-sm font-medium leading-6"
        >
            <x-tooltip
                id="tooltip-refresh-report"
                placement="bottom"
                whitespace="whitespace-nowrap"
                message="{{ trans('reports.last_updated', ['date' => company_date($cached_at), 'time' => $cached_at->isoFormat('LT')]) }}"
            >
                {{-- flex, not inline-block: on the text baseline the icon sits above the button's centre --}}
                <span class="material-icons flex" x-bind:class="{ 'animate-spin': refreshing }">refresh</span>
            </x-tooltip>
        </x-link>
    </div>
@endif
