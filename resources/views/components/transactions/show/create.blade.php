@php $created_date = '<span class="font-medium">' . company_date($transaction->created_at) . '</span>' @endphp

<x-show.accordion type="create">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.create') }}"
        />

        <div class="text-black-400 text-sm flex gap-x-1 mt-1">
            @if ($transaction->isRecurringTransaction())
                {!! trans('transactions.slider.create_recurring', ['user' => $transaction->owner->name, 'date' => $created_date]) !!}
            @else
                {!! trans('transactions.slider.create', ['user' => $transaction->owner->name, 'date' => $created_date]) !!}
            @endif
        </div>
    </x-slot>

    <x-slot name="body">
        @if ($transaction->isNotTransferTransaction())
            <div class="flex my-3 space-x-2 rtl:space-x-reverse">
                <x-link
                    href="{{ route($routeButtonEdit, [$transaction->id, 'type' => $transaction->type]) }}"
                    class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-xs font-bold leading-6"
                    override="class"
                >
                    {{ trans('general.edit') }}
                </x-link>
            </div>
        @endif
    </x-slot>
</x-show.accordion>