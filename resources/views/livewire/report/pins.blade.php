<div class="flex flex-wrap lg:flex-nowrap items-center justify-between my-16">
    @foreach ($reports as $report)
        <x-link href="{{ route('reports.show', $report->id) }}" class="w-6/12 lg:w-2/12 text-center px-3 group {{ ($loop->count == 6 && $loop->last) ? '' : 'border-r border-gray-300' }}"
            override="class"
        >
            <span class="material-icons-outlined text-4xl transition-all text-black-400">
                {{ $icons[$report->id] }}
            </span>
            
            <div class="h-10 font-medium text-sm mt-2">
                <x-link.hover group-hover>
                    {!! $report->name !!}
                </x-link.hover>
            </div>
        </x-link>
    @endforeach

    @for ($i = 6; $i > $reports->count(); $i--)
        <div
            @class([
                'w-6/12 lg:w-2/12 text-center opacity-20 px-3',
                'border-r border-gray-300' => ($i-1 == $reports->count()) ? false : true,
            ])
        >
            <span class="material-icons-outlined text-4xl transform rotate-45">push_pin</span>

            <div class="h-10 font-medium text-sm mt-2">
                {{ trans('reports.pin') }}
            </div>
        </div>
    @endfor
</div>
