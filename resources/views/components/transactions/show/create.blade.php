<x-show.accordion type="create">
    <x-slot name="head">
        @php
            $created_date = '<span class="font-medium">' . company_date($transaction->created_at) . '</span>';
            $description = trans('transactions.slider.create', ['user' => $transaction->owner->name, 'date' => $created_date]);

            if ($transaction->isRecurringTransaction()) {
                $description = trans('transactions.slider.create_recurring', ['user' => $transaction->owner->name, 'date' => $created_date]);
            }
        @endphp

        <x-show.accordion.head
            title="{{ trans('general.create') }}"
            :description="$description"
        />
    </x-slot>

    <x-slot name="body">
        @if ($transaction->isNotTransferTransaction())
            <div class="flex my-3 space-x-2 rtl:space-x-reverse">
                <x-link href="{{ route($routeButtonEdit, [$transaction->id, 'type' => $transaction->type]) }}" id="show-slider-actions-edit-{{ $transaction->type }}" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-xs font-bold leading-6">
                    {{ trans('general.edit') }}
                </x-link>
            </div>
        @endif
    </x-slot>
</x-show.accordion>
