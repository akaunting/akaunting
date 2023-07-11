<div class="w-full lg:max-w-6xl px-4 lg:px-0  m-auto">
    <x-layouts.signed>
        <x-slot name="title">
            {{ setting('invoice.title', trans_choice('general.invoices', 1)) . ': ' . $invoice->document_number }}
        </x-slot>

        <x-slot name="buttons">
            @stack('button_pdf_start')
            <x-link href="{{ $pdf_action }}" class="bg-green text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-lg text-sm font-medium leading-6 hover:bg-green-700">
                {{ trans('general.download') }}
            </x-link>
            @stack('button_pdf_end')

            @stack('button_print_start')
            <x-link href="{{ $print_action }}" target="_blank" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
                {{ trans('general.print') }}
            </x-link>
            @stack('button_print_end')

            @stack('button_dashboard_start')
            @if (! user())
                <x-link href="{{ route('portal.dashboard') }}" class="px-3 py-1.5 mb-3 sm:mb-0 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium leading-6">
                    {{ trans('invoices.all_invoices') }}
                </x-link>
            @endif
            @stack('button_dashboard_end')
        </x-slot>

        <x-slot name="content">
            <div class="flex flex-col lg:flex-row my-10 lg:space-x-24 rtl:space-x-reverse space-y-4 lg:space-y-0">
                <div class="w-full lg:w-5/12">
                    @if (! empty($payment_methods) && ! in_array($invoice->status, ['paid', 'cancelled']))
                        <div class="tabs w-full" x-data="{ active: '{{ reset($payment_methods) }}' }">
                            <div role="tablist" class="flex flex-wrap">
                                @php $is_active = true; @endphp

                                <div class="{{ count($payment_methods) > 3 ? 'swiper swiper-links': 'w-full flex' }}">
                                    <div class="{{ count($payment_methods) > 3 ? 'swiper-wrapper' : 'inline-flex' }}">
                                        @foreach ($payment_methods as $key => $name)
                                            @stack('invoice_{{ $key }}_tab_start')
                                                <div
                                                    x-on:click="active = '{{ $name }}'"
                                                    @click="onChangePaymentMethodSigned('{{ $key }}')"
                                                    id="tabs-payment-method-{{ $key }}-tab"
                                                    x-bind:class="active != '{{ $name }}' ? '' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                                                    class="relative px-8 text-sm text-center pb-2 cursor-pointer transition-all border-b whitespace-nowrap tabs-link {{ count($payment_methods) > 3 ? 'swiper-slide': '' }}"
                                                >
                                                    {{ $name }}
                                                </div>
                                            @stack('invoice_{{ $key }}_tab_end')

                                            @php $is_active = false; @endphp
                                        @endforeach
                                    </div>

                                    @if (count($payment_methods) > 3)
                                        <div class="swiper-button-next bg-body text-white flex items-center justify-center right-0 top-3">
                                            <span class="material-icons text-purple text-4xl">chevron_right</span>
                                        </div>

                                        <div class="swiper-button-prev bg-body text-white flex items-center justify-center left-0 top-3">
                                            <span class="material-icons text-purple text-4xl">chevron_left</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @php $is_active = true; @endphp

                            @foreach ($payment_methods as $key => $name)
                                @stack('invoice_{{ $key }}_content_start')
                                    <div
                                        x-bind:class="active != '{{ $name }}' ? 'hidden': 'block'"
                                        class="my-3"
                                        id="tabs-payment-method-{{ $key }}"
                                    >
                                        <component v-bind:is="method_show_html" @interface="onRedirectConfirm"></component>
                                    </div>
                                @stack('invoice_{{ $key }}_content_end')

                                @php $is_active = false; @endphp
                            @endforeach

                            <x-form id="portal">
                                <x-form.group.payment-method
                                    id="payment-method"
                                    :selected="array_key_first($payment_methods)"
                                    not-required
                                    form-group-class="invisible"
                                    placeholder="{{ trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)]) }}"
                                    change="onChangePaymentMethodSigned"
                                />

                                <x-form.input.hidden name="document_id" :value="$invoice->id" v-model="form.document_id" />
                            </x-form>
                        </div>
                    @endif

                    @if ($invoice->transactions->count())
                        <x-show.accordion type="transactions" open>
                            <x-slot name="head">
                                <x-show.accordion.head
                                    title="{{ trans_choice('general.transactions', 2) }}"
                                    description=""
                                />
                            </x-slot>

                            <x-slot name="body" class="block" override="class">
                                <div class="text-xs mt-1" style="margin-left: 0 !important;">
                                    <span class="font-medium">
                                        {{ trans('invoices.payments_received') }}:
                                    </span>

                                    @if ($invoice->transactions->count())
                                        @foreach ($invoice->transactions as $transaction)
                                            <div class="my-2">
                                                <span>
                                                    <x-link href="{{ \URL::signedRoute('signed.payments.show', [$transaction->id]) }}" class="text-black bg-no-repeat bg-0-2 bg-0-full hover:bg-full-2 bg-gradient-to-b from-transparent to-black transition-backgroundSize" override="class">
                                                        <x-date :date="$transaction->paid_at" />
                                                    </x-link>
                                                    - {!! trans('documents.transaction', [
                                                        'amount' => '<span class="font-medium">' . money($transaction->amount, $transaction->currency_code) . '</span>',
                                                        'account' => '<span class="font-medium">' . $transaction->account->name . '</span>',
                                                    ]) !!}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="my-2">
                                            <span>{{ trans('general.no_records') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </x-slot>
                        </x-show.accordion>
                    @endif
                </div>

                <div class="w-full lg:w-7/12">
                    <x-documents.show.template
                        type="invoice"
                        :document="$invoice"
                        document-template="{{ setting('invoice.template', 'default') }}"
                    />
                </div>
            </div>
        </x-slot>

        @push('stylesheet')
            <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
        @endpush

        @push('scripts_start')
            <script type="text/javascript">
                var payment_action_path = {!! json_encode($payment_actions) !!};
            </script>
        @endpush

        <x-script folder="portal" file="apps" />
    </x-layouts.signed>
</div>
