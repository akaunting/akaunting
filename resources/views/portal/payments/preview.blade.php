<x-layouts.preview>
    <x-slot name="title">
        {{ trans_choice('general.payments', 1) . ': '}} <x-date date="{{ $payment->paid_at }}" />
    </x-slot>

    <x-slot name="sticky" description="{{ trans('transactions.sticky.description') }}" url="{{ route('transactions.show', $payment->id) }}"></x-slot>

    <x-slot name="buttons">
        @stack('button_pdf_start')
        <x-link href="{{ route('preview.payments.pdf', $payment->id) }}" class="bg-green text-white px-3 py-1.5 mb-3 sm:mt-2 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-green-700">
            {{ trans('general.download') }}
        </x-link>
        @stack('button_pdf_end')

        @stack('button_print_start')
        <x-link href="{{ route('preview.payments.print', $payment->id) }}" target="_blank" class="px-3 py-1.5 mb-3 sm:mt-2 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
            {{ trans('general.print') }}
        </x-link>
        @stack('button_print_end')
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-12 lg:space-y-0">
            <div class="w-full lg:w-5/12 space-y-8">
                <div class="border-b-2 border-gray-200 pb-1">
                    <div class="relative w-full text-left group">
                        <x-button.hover group-hover>
                            {{ trans('portal.received_date') }}
                        </x-button.hover>
                    </div>

                    <div class="relative overflow-hidden transition-all duration-700">
                        <span class="text-sm">
                            <x-date date="{{ $payment->paid_at }}" />
                        </span>
                    </div>
                </div>

                <!--Status partials-->
                @if ($payment->document)
                <div class="border-b-2 border-gray-200 pb-1">
                    <div class="relative w-full ltr:text-left rtl:text-right">
                        <span class="font-medium">
                            {{ trans('portal.payments') }}
                        </span>
                    </div>

                    <span class="text-sm">
                        {{ trans('portal.payment_detail.description', ['date' => date($payment->paid_at), 'amount' => money($payment->amount, $payment->currency_code)]) }}
                    </span>
                </div>
                @endif
            </div>

            <div class="w-full lg:w-7/12">
                <x-transactions.show.template
                    type="payment"
                    :transaction="$payment"
                    transaction-template="{{ setting('payment.template', 'default') }}"
                    hide-payment-methods
                />
            </div>
        </div>
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-script folder="portal" file="apps" />
</x-layouts.preview>
