<x-show.accordion type="children" :open="($accordionActive == 'children')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.invoices', 2) }}"
            description="{!! trans('documents.slider.children', [
                'count' => $document->children()->count(),
                'type' => $type_lowercase,
            ]) !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        @stack('timeline_children_body_start')

        @if ($document->children()->count())
            @foreach ($document->children()->get() as $child)
                @php
                    $url = '<a href="' . route(Str::replace('recurring-', '', $showRoute), $child->id) . '" class="text-purple">' . $child->document_number . '</a>';
                @endphp

                <div class="my-2">
                    {!! trans('recurring.child', ['url' => $url, 'date' => company_date($child->created_at)]) !!}
                </div>
            @endforeach
        @else
            {{ trans('general.none') }}
        @endif

        @stack('timeline_children_body_end')
    </x-slot>
</x-show.accordion>
