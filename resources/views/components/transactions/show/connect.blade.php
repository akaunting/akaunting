<x-show.accordion type="connect">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('transactions.connected') }}"
            description="{!! trans('transactions.slider.connect', ['count' => $transaction->splits()->count()]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        @if ($transaction->splits()->count())
            @foreach ($transaction->splits()->get() as $split)
                @php $url = '<a href="' . route('transactions.show', $split->id) . '" class="text-purple">' . $split->number . '</a>' @endphp

                <div class="my-2">
                    {!! trans('recurring.child', ['url' => $url, 'date' => company_date($split->paid_at)]) !!}
                </div>
            @endforeach
        @else
            {{ trans('general.none') }}
        @endif
    </x-slot>
</x-show.accordion>
