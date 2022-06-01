<x-show.accordion type="children" :open="($accordionActive == 'children')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.invoices', 2) }}"
            description="{!! trans('invoices.slider.children', ['count' => $document->children()->count()]) !!}"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        <div class="flex my-3 space-x-2 rtl:space-x-reverse">
            @if ($document->children()->count())
                @foreach ($document->children() as $child)
                    @php $url = '<a href="' . route('transactions.show', $child->id) . '" class="text-purple" @click="e => e.target.classList.add(\'disabled\')">' . $child->number . '</a>' @endphp

                    <div class="my-2">
                        {{ trans('recurring.child', ['url' => $url, 'date' => company_date($child->due_at)]) }}
                    </div>
                @endforeach
            @else
                {{ trans('general.none') }}
            @endif
        </div>
    </x-slot>
</x-show.accordion>
