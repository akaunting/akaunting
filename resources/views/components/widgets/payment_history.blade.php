<div class="flex flex-col text-sm text-black-400">
    <div class="font-medium text-black mb-1">
        {{ trans('portal.payment_history.title') }}
    </div>

    @if ($payments->count())
        @foreach ($payments as $item)
        <span class="text-xs mb-3">
            @if (! $item->document)
            {{ trans('portal.payment_history.description', ['date' => company_date($item->created_at), 'amount' => money($item->amount, $item->currency_code)]) }}
            @else
            {{ trans('portal.payment_history.invoice_description', ['date' => company_date($item->created_at), 'amount' => money($item->amount, $item->currency_code), 'invoice_nember' => $item->document->document_number]) }}
            @endif
        </span>
        @endforeach

        @if ($payments->count() > 2)
        <x-link href="{{ route('portal.payments.index') }}" class="underline hover:text-black-700" override="class">
            {{ trans('modules.see_all_type', ['type' => trans_choice('general.payments', 2)]) }}
        </x-link>
        @endif
    @else
        <span class="text-xs">
            {{ trans('portal.payment_history.no_data') }}
        </span>
    @endif
</div>
