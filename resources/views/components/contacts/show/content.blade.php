<x-show.container>
    <x-show.summary>
        @stack('profile_start')

        @if (! $hideTopLeft)
        <x-show.summary.left>
            @if (! $hideAvatar)
            <x-slot name="avatar">
                {{ $contact->initials }}
            </x-slot>
            @endif

            @stack('contact_email_start')
            @if (! $hideEmail)
            <span>{{ $contact->email }}</span>
            @endif
            @stack('contact_email_end')

            @stack('contact_phone_start')
            @if (! $hidePhone)
            <span>{{ $contact->phone }}</span>
            @endif
            @stack('contact_phone_end')
        </x-show.summary.left>
        @endif

        @stack('profile_end')

        @if (! $hideTopRight)
        <x-show.summary.right>
            @stack('summary_overdue_start')
            @if (! $hideOverdue)
                <x-slot name="first" amount="{{ money($totals['overdue'], setting('default.currency'), true) }}" title="{{ trans('general.overdue') }}"></x-slot>
            @endif
            @stack('summary_overdue_end')

            @stack('summary_open_start')
            @if (! $hideOpen)
                <x-slot name="second" amount="{{ money($totals['open'], setting('default.currency'), true) }}" title="{{ trans('general.open') }}"></x-slot>
            @endif
            @stack('summary_open_end')

            @stack('summary_paid_start')
            @if (! $hidePaid)
                <x-slot name="third" amount="{{ money($totals['paid'], setting('default.currency'), true) }}" title="{{ trans('general.paid') }}"></x-slot>
            @endif
            @stack('summary_paid_end')
        </x-show.summary.right>
        @endif
    </x-show.summary>

    <x-show.content>
        @if (! $hideBottomLeft)
        <x-show.content.left>
            @stack('customer_address_start')
            @if (! $hideAddress)
            <div class="flex flex-col text-sm mb-5">
                <div class="font-medium">{{ trans('general.address') }}</div>
                <span>{{ $contact->address }}<br>{{ $contact->location }}</span>
            </div>
            @endif
            @stack('customer_address_end')

            @stack('customer_tax_number_start')
            @if (! $hideTaxNumber)
            @if ($contact->tax_number)
                <div class="flex flex-col text-sm mb-5">
                    <div class="font-medium">{{ trans('general.tax_number') }}</div>
                    <span>{{ $contact->tax_number }}</span>
                </div>
            @endif
            @endif
            @stack('customer_tax_number_end')

            @stack('customer_website_start')
            @if (! $hideWebsite)
            @if ($contact->website)
                <div class="flex flex-col text-sm mb-5">
                    <div class="font-medium">{{ trans('general.website') }}</div>
                    <span>{{ $contact->website }}</span>
                </div>
            @endif
            @endif
            @stack('customer_website_end')

            @stack('customer_reference_start')
            @if (! $hideReference)
            @if ($contact->reference)
                <div class="flex flex-col text-sm mb-5">
                    <div class="font-medium">{{ trans('general.reference') }}</div>
                    <span>{{ $contact->reference }}</span>
                </div>
            @endif
            @endif
            @stack('customer_reference_end')

            @stack('customer_client_portal_start')
                @if (! $hideUser)
                    <div class="flex flex-col text-sm mb-5">
                        <div class="flex items-center font-medium">
                            <div class="flex items-center cursor-default">
                                <div data-tooltip-target="tooltip-client-describe" data-tooltip-placement="bottom">
                                    {{ trans('general.client_portal') }}
                                </div>

                                @if ($contact->user)
                                    <span data-tooltip-target="tooltip-client-permission" data-tooltip-placement="bottom" class="material-icons text-green text-base ltr:ml-1 rtl:mr-1">check</span>
                                @else
                                    <span data-tooltip-target="tooltip-client-permission" data-tooltip-placement="bottom" class="material-icons-round text-red text-sm ltr:ml-1 rtl:mr-1">hide_source</span>
                                @endif

                                <div id="tooltip-client-describe" role="tooltip" class="w-2/12 inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-normal tooltip-content">
                                    {{ trans('customers.client_portal_description') }}
                                    <div class="absolute w-2 h-2 -top-1 -left-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
                                </div>

                                <div id="tooltip-client-permission" role="tooltip" class="inline-block absolute invisible z-20 py-1 px-2 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 shadow-sm opacity-0 whitespace-nowrap tooltip-content">
                                    @if ($contact->user)
                                        {{ trans('customers.client_portal_text.can') }}
                                    @else
                                        {{ trans('customers.client_portal_text.cant') }}
                                    @endif
                                    <div class="absolute w-2 h-2 -top-1 -left-1 before:content-[' '] before:absolute before:w-2 before:h-2 before:bg-white before:border-gray-200 before:transform before:rotate-45 before:border before:border-b-0 before:border-r-0" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @stack('customer_client_portal_end')
        </x-show.content.left>
        @endif

        @if (! $hideBottomRight)
        <x-show.content.right>
            <x-tabs active="documents">
                <x-slot name="navs">
                    @stack('documents_nav_start')

                    <x-tabs.nav
                        id="documents"
                        name="{{ trans_choice($textDocument, 2) }}"
                        active
                        class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                    />

                    @stack('transactions_nav_start')

                    <x-tabs.nav
                        id="transactions"
                        name="{{ trans_choice('general.transactions', 2) }}"
                        class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                    />

                    @stack('transactions_nav_end')
                </x-slot>

                <x-slot name="content">
                    @stack('documents_tab_start')

                    <x-tabs.tab id="documents">
                        @if ($documents->count())
                            <x-table>
                                <x-table.thead>
                                    <x-table.tr class="flex items-center px-1">
                                        <x-table.th class="w-4/12 table-title hidden sm:table-cell">
                                            <x-slot name="first">
                                                <x-sortablelink column="due_at" title="{{ trans('invoices.due_date') }}" />
                                            </x-slot>

                                            <x-slot name="second">
                                                <x-sortablelink column="issued_at" title="{{ trans('invoices.invoice_date') }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-3/12 table-title hidden sm:table-cell">
                                            <x-sortablelink column="status" title="{{ trans_choice('general.statuses', 1) }}" />
                                        </x-table.th>

                                        <x-table.th class="w-6/12 sm:w-3/12 table-title'">
                                            <x-slot name="first">
                                                <x-sortablelink column="contact_name" title="{{ trans_choice('general.customers', 1) }}" />
                                            </x-slot>

                                            <x-slot name="second">
                                                <x-sortablelink column="document_number" title="{{ trans_choice('general.numbers', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-6/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-sm font-medium text-black tracking-wider" override="class">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($documents as $item)
                                        @php $paid = $item->paid; @endphp
                                        <x-table.tr href="{{ route(config('type.document.' . $item->type . '.route.prefix', 'invoices') . '.show', $item->id) }}">
                                            <x-table.td class="w-4/12 table-title hidden sm:table-cell">
                                                <x-slot name="first" class="font-bold truncate" override="class">
                                                    {{ \Date::parse($item->due_at)->diffForHumans() }}
                                                </x-slot>

                                                <x-slot name="second">
                                                    <x-date date="{{ $item->issued_at }}" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-3/12 table-title hidden sm:table-cell">
                                                <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" text-color="text-text-{{ $item->status_label }}" />
                                            </x-table.td>

                                            <x-table.td class="w-6/12 sm:w-3/12 table-title'">
                                                <x-slot name="first">
                                                    {{ $item->contact_name }}
                                                </x-slot>

                                                <x-slot name="second" class="relative w-20 font-normal group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class,data-tooltip-target,data-tooltip-placement">
                                                    <span class="border-black border-b border-dashed">
                                                        {{ $item->document_number }}
                                                    </span>

                                                    <div class="w-full absolute h-10 -left-10 -mt-6"></div>

                                                    <x-documents.index.information :document="$item" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-6/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-sm font-medium text-black tracking-wider" override="class">
                                                <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                            </x-table.td>

                                            <x-table.td kind="action">
                                                <x-table.actions :model="$item" />
                                            </x-table.td>
                                        </x-table.tr>
                                    @endforeach
                                </x-table.tbody>
                            </x-table>

                            <x-pagination :items="$documents" />
                        @else
                            <x-show.no-records type="{{ $type }}" :model="$contact" :group="config('type.contact.' . $type . '.group')" :page="\Str::plural(config('type.contact.' . $type . '.document_type'))" />
                        @endif
                    </x-tabs.tab>

                    @stack('transactions_tab_start')

                    <x-tabs.tab id="transactions">
                        @if ($transactions->count())
                            <x-table>
                                <x-table.thead>
                                    <x-table.tr class="flex items-center px-1">
                                        <x-table.th class="w-4/12 sm:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="number" title="{{ trans_choice('general.numbers', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-2/12 hidden sm:table-cell">
                                            <x-slot name="first">
                                                <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-3/12">
                                            <x-sortablelink column="account.name" title="{{ trans_choice('general.accounts', 1) }}" />
                                        </x-table.th>

                                        <x-table.th class="w-2/12 hidden sm:table-cell">
                                            <x-slot name="first">
                                                <x-sortablelink column="contact.name" title="{{ trans_choice('general.contacts', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="document.document_number" title="{{ trans_choice('general.documents', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-xs font-medium text-black tracking-wider" override="class">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($transactions as $item)
                                        <x-table.tr href="{{ route('transactions.show', $item->id) }}">
                                            <x-table.td class="w-4/12 sm:w-3/12">
                                                <x-slot name="first" class="font-bold truncate" override="class">
                                                    <x-date date="{{ $item->paid_at }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    {{ $item->number }}
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-2/12 hidden sm:table-cell">
                                                <x-slot name="first">
                                                    {{ $item->type_title }}
                                                </x-slot>
                                                <x-slot name="second" class="flex items-center">
                                                    <x-index.category :model="$item->category" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 sm:w-3/12">
                                                {{ $item->account->name }}
                                            </x-table.td>

                                            <x-table.td class="w-2/12 hidden sm:table-cell">
                                                <x-slot name="first">
                                                    {{ $item->contact->name }}
                                                </x-slot>
                                                <x-slot name="second">
                                                    @if ($item->document)
                                                        <a href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed">
                                                            {{ $item->document->document_number }}
                                                        </a>
                                                    @else
                                                        <x-empty-data />
                                                    @endif
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="relative w-4/12 sm:w-2/12 ltr:pl-6 rtl:pr-6 py-3 ltr:text-right rtl:text-left text-sm font-normal text-black tracking-wider" override="class">
                                                <x-money :amount="$item->amount" :currency="$item->currency_code" convert />
                                            </x-table.td>

                                            <x-table.td kind="action">
                                                <x-table.actions :model="$item" />
                                            </x-table.td>
                                        </x-table.tr>
                                    @endforeach
                                </x-table.tbody>
                            </x-table>

                            <x-pagination :items="$transactions" />
                        @else
                            <x-show.no-records type="{{ $type }}" :model="$contact" group="banking" page="transactions" />
                        @endif
                    </x-tabs.tab>

                    @stack('transactions_tab_end')
                </x-slot>
            </x-tabs>
        </x-show.content.right>
        @endif
    </x-show.content>
</x-show.container>
