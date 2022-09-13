<x-show.accordion type="children">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans_choice('general.transactions', 2) }}"
            description="{!! trans('transactions.slider.children', ['count' => $transaction->children()->count()]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        @if ($transaction->children()->count())
            @foreach ($transaction->children()->get() as $child)
                @php $url = '<a href="' . route('transactions.show', $child->id) . '" class="text-purple">' . $child->number . '</a>' @endphp

                <div class="my-2">
                    {!! trans('recurring.child', ['url' => $url, 'date' => company_date($child->paid_at)]) !!}
                </div>
            @endforeach
        @else
            {{ trans('general.none') }}
        @endif
    </x-slot>
</x-show.accordion>
