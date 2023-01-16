<x-show.accordion type="children" :open="($accordionActive == 'children')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.invoices', 2) }}"
            description="{!! trans('invoices.slider.children', ['count' => $document->children()->count()]) !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
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
    </x-slot>
</x-show.accordion>
