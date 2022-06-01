<div class="flex flex-wrap lg:flex-nowrap items-center justify-between my-16">
    @foreach ($reports as $report)
        <a href="{{ route('reports.show', $report->id) }}"
            @class([
                'w-6/12 lg:w-2/12 text-center px-3 group',
                'border-r border-gray-300' => ($loop->count == 6 && $loop->last) ? false : true,
            ])
        >
            <span class="material-icons-outlined text-4xl transition-all text-black-400">{{ $icons[$report->id] }}</span>
            <div class="h-10 font-medium text-sm mt-2">
                <span class="border-b border-transparent transition-all group-hover:border-black">
                    {{ $report->name }}
                </span>
            </div>
        </a>
    @endforeach

    @for ($i = 6; $i > $reports->count(); $i--)
        <div
            @class([
                'w-6/12 lg:w-2/12 text-center opacity-20 px-3',
                'border-r border-gray-300' => ($i-1 == $reports->count()) ? false : true,
            ])
        >
            <span class="material-icons-outlined text-4xl transform rotate-45">push_pin</span>
            <div class="h-10 font-medium text-sm mt-2">{{ trans('reports.pin') }}</div>
        </div>
    @endfor
</div>
