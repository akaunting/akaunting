<x-layouts.portal>
    <x-slot name="title">
        {{ trans_choice('general.payments', 2) }}
    </x-slot>

    <x-slot name="content">
        @if ($payments->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search search-string="App\Models\Portal\Banking\Transaction" />

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th override="class" class="p-0"></x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12">
                                <x-sortablelink column="payment_method" title="{{ trans_choice('general.payment_methods', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12 sm:w-3/12" hidden-mobile>
                                <x-sortablelink column="description" title="{{ trans('general.description') }}" />
                            </x-table.th>

                            <x-table.th class="w-3/12" kind="amount">
                                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($payments as $item)
                            <x-table.tr href="{{ route('portal.payments.show', $item->id) }}">
                                <x-table.td kind="action"></x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <span class="font-bold"><x-date date="{{ $item->paid_at }}" /></span>
                                </x-table.td>

                                <x-table.td class="w-4/12 sm:w-3/12">
                                    <x-payment-method :method="$item->payment_method" type="customer" />
                                </x-table.td>

                                <x-table.td class="w-3/12" hidden-mobile>
                                    {{ $item->description }}
                                </x-table.td>

                                <x-table.td class="w-3/12" kind="amount">
                                    <x-money :amount="$item->amount" :currency="$item->currency_code" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$payments" />
            </x-index.container>
        @else
            <div class="flex">
                <div class="w-full text-center">
                    <div class="my-10">
                        {{ trans('portal.create_your_invoice') }}
                    </div>

                    <div class="my-10">
                        <x-link
                            href="https://akaunting.com/accounting-software?utm_source=software&utm_medium=payment_index&utm_campaign=plg"
                            class="bg-purple text-white px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 hover:bg-purple-700"
                            override="class"
                        >
                            {{ trans('portal.get_started') }}
                        </x-link>
                    </div>

                    <div class="my-10">
                        <img src="https://assets.akaunting.com/software/portal/payment.gif" class="inline" alt="Get Started" />
                    </div>
                </div>
            </div>

            @push('css')
                <style>
                    .hide-empty-page {
                        display: none;
                    }
                </style>
            @endpush
        @endif
    </x-slot>

    <x-script folder="portal" file="apps" />
</x-layouts.portal>
