{{-- Lightened on hover only when the whole card leads somewhere; otherwise it reads as a click that does nothing --}}
<div {{ $attributes->merge(['class' => 'relative flex flex-col p-4 bg-white border border-gray-200 rounded-xl' . ($href ? ' hover:bg-gray-100' : '')]) }}>
    @if ($href)
        <a href="{{ $href }}" class="absolute inset-0" aria-label="{{ $label }}"></a>
    @endif

    {{-- Positioned, so it paints over the link it follows, but with no layer of its own: one
         would trap an open menu below the cards that come after this one --}}
    <div class="relative flex items-center justify-between pointer-events-none">
        <div class="flex items-start min-w-0">
            @if (! $hideBulkAction)
                <div class="pointer-events-auto">
                    <x-index.bulkaction.single id="{{ $model->id }}" name="{{ $label }}" />
                </div>
            @endif

            <div @class(['min-w-0', 'ltr:ml-3 rtl:mr-3' => ! $hideBulkAction])>
                @if (! empty($title))
                    <div class="flex items-center text-sm font-bold">
                        {!! $title !!}
                    </div>
                @endif

                @if (! empty($subtitle))
                    <div class="text-xs text-black-400 truncate">
                        {!! $subtitle !!}
                    </div>
                @endif

                {{-- Keyed by record: a stack shared by every card would print the same marks
                     on each of them --}}
                @if (! $hideBulkAction)
                    @stack($model->getTable() . '_card_marks_' . $model->id)
                @endif
            </div>
        </div>

        @if (! empty($actions))
            <div class="relative shrink-0 ltr:ml-2 rtl:mr-2 pointer-events-auto">
                {!! $actions !!}
            </div>
        @endif
    </div>

    @if (! empty($slot) && $slot->isNotEmpty())
        <div class="relative {{ $bodyClass }} pointer-events-none">
            {!! $slot !!}
        </div>
    @endif
</div>
