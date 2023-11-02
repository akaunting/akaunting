<div class="flex flex-col text-sm text-black-400">
    <div class="font-medium text-black mb-1">
        {{ trans('portal.last_payment.title') }}
    </div>

    @if (! empty($payment))
        <span class="text-xs">
            {{ trans('portal.last_payment.description', ['date' => company_date($payment->created_at)]) }}
        </span>

        <span class="text-xl text-black my-3">
            <x-money :amount="$payment->amount" :currency="$payment->currency_code" />
        </span>
    @else
        <span class="text-xs">
            {{ trans('portal.last_payment.not_payment') }}
        </span>
    @endif
</div>
