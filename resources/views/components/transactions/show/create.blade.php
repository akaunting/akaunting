@php $created_date = '<span class="font-medium">' . company_date($transaction->created_at) . '</span>' @endphp

<div class="border-b pb-4" x-data="{ create : null }">
    <button class="relative w-full text-left group"
        x-on:click="create !== 1 ? create = 1 : create = null"
    >
        <span class="font-medium">
            <x-button.hover>
                {{ trans('general.create') }}
            </x-button.hover>
        </span>

        <div class="text-black-400 text-sm">
            @if ($transaction->isRecurringTransaction())
                {!! trans('transactions.slider.create_recurring', ['user' => $transaction->owner->name, 'date' => $created_date]) !!}
            @else
                {!! trans('transactions.slider.create', ['user' => $transaction->owner->name, 'date' => $created_date]) !!}
            @endif
        </div>

        <span class="material-icons absolute right-0 top-0 transition-all transform" x-bind:class="create === 1 ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div class="overflow-hidden transition-transform origin-top-left ease-linear duration-100"
        x-ref="container1"
        x-bind:class="create == 1 ? 'h-auto ' : 'scale-y-0 h-0'"
    >
        <div class="flex my-3 space-x-2 rtl:space-x-reverse">
            <a href="{{ route($routeButtonEdit, [$transaction->id, 'type' => $transaction->type]) }}" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-xl text-purple text-xs font-bold leading-6">
                {{ trans('general.edit') }}
            </a>
        </div>
    </div>
</div>
