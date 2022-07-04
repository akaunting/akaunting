<div class="border-b pb-4" x-data="{ children : null }">
    <button class="relative w-full text-left cursor-pointer group"
        x-on:click="children !== 1 ? children = 1 : children = null"
    >
        <span class="font-medium">
            <x-button.hover group-hover>
                {{ trans_choice('general.transactions', 2) }}
            </x-button.hover>
        </span>

        <div class="text-black-400 text-sm">
            {!! trans('transactions.slider.children', ['count' => $transaction->children()->count()]) !!}
        </div>

        <span class="material-icons absolute right-0 top-0 transition-all transform"
            x-bind:class="children === 1 ? 'rotate-180' : ''"
        >expand_more</span>
    </button>

    <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
        x-ref="container1"
        x-bind:class="children == 1 ? 'h-auto ' : 'scale-y-0 h-0'"
    >
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
    </div>
</div>
