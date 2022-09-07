@php $created_date = '<span class="font-medium">' . company_date($transfer->created_at) . '</span>' @endphp

<x-show.accordion type="create">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.create') }}"
            description="{!! trans('transactions.slider.create', ['user' => $transfer->owner->name, 'date' => $created_date]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex my-3 space-x-2 rtl:space-x-reverse">
            <x-link href="{{ route('transfers.edit', $transfer->id) }}" id="show-slider-actions-edit-transfer" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-xs font-bold leading-6">
                {{ trans('general.edit') }}
            </x-link>
        </div>
    </x-slot>
</x-show.accordion>
