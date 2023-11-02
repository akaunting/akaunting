<div class="flex flex-col text-sm text-black-400">
    <div class="font-medium text-black mb-1">
        {{ trans('portal.outstanding_balance.title') }}
    </div>

    @if ($contact)
        <span class="text-xs">
            {{ trans('portal.outstanding_balance.description') }}
        </span>

        <div class="flex flex-col items-start my-3">
            <span class="text-xl text-black">
                <x-money :amount="$contact->overdue" :currency="$contact->currency_code" />
            </span>

            <x-link href="{{ route('portal.invoices.index') }}" class="px-2 py-1 my-3 rounded-lg text-xs leading-6 bg-green text-white hover:bg-green-700 disabled:bg-green-100" override="class">
                {{ trans('bills.make_payment') }}
            </x-link>
        </div>
    @else
        <span class="text-xs">
            {{ trans('portal.outstanding_balance.not_payment') }}
        </span>
    @endif
</div>
