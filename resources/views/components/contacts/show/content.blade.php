<x-show.container>
    <x-show.summary>
        @stack('profile_start')

        @if (! $hideTopLeft)
        <x-show.summary.left>
                @if (! $hideAvatar)
                <x-slot name="avatar">
                    @if ($contact->logo)
                        @if (is_object($contact->logo))
                            <img src="{{ Storage::url($contact->logo->id) }}" class="absolute w-12 h-12 rounded-full hidden lg:block" alt="{{ $contact->name }}" title="{{ $contact->name }}">
                        @else
                            <img src="{{ asset('public/img/user.svg') }}" class="absolute w-12 h-12 rounded-full hidden lg:block" alt="{{ $contact->name }}"/>
                        @endif

                        {{ $contact->initials }}
                    @else
                        {{ $contact->initials }}
                    @endif
                </x-slot>
                @endif

            @if (! $hideEmail)
            <span>{{ $contact->email }}</span>
            @endif

            @if (! $hidePhone)
            <span>{{ $contact->phone }}</span>
            @endif
        </x-show.summary.left>
        @endif

        @stack('profile_end')

        @if (! $hideTopRight)
        <x-show.summary.right>
            @stack('summary_overdue_start')
            @if (! $hideOverdue)
                <x-slot name="first"
                    amount="{{ $summary_amounts['overdue_for_humans'] }}"
                    title="{{ trans('general.overdue') }}"
                    tooltip="{{ $summary_amounts['overdue_exact'] }}"
                ></x-slot>
            @endif
            @stack('summary_overdue_end')

            @stack('summary_open_start')
            @if (! $hideOpen)
                <x-slot name="second"
                    amount="{{ $summary_amounts['open_for_humans'] }}"
                    title="{{ trans('general.open') }}"
                    tooltip="{{ $summary_amounts['open_exact'] }}"
                ></x-slot>
            @endif
            @stack('summary_open_end')

            @stack('summary_paid_start')
            @if (! $hidePaid)
                <x-slot name="third"
                    amount="{{ $summary_amounts['paid_for_humans'] }}"
                    title="{{ trans('general.paid') }}"
                    tooltip="{{ $summary_amounts['paid_exact'] }}"
                ></x-slot>
            @endif
            @stack('summary_paid_end')
        </x-show.summary.right>
        @endif
    </x-show.summary>

    <x-show.content>
        @if (! $hideBottomLeft)
        <x-show.content.left>

            @stack('name_input_start')
            @stack('name_input_end')

            @stack('logo_input_start')
            @stack('logo_input_end')

            @stack('email_input_start')
            @stack('email_input_end')

            @stack('phone_input_start')
            @stack('phone_input_end')

            @stack('currency_code_input_start')
            @stack('currency_code_input_end')

            @stack('address_input_start')
            @if (! $hideAddress)
            <div class="flex flex-col text-sm sm:mb-5">
                <div class="font-medium">{{ trans('general.address') }}</div>
                <span>{{ $contact->address }}<br>{{ $contact->location }}</span>
            </div>
            @endif
            @stack('address_input_end')

            @stack('city_input_start')
            @stack('city_input_end')

            @stack('zip_code_input_start')
            @stack('zip_code_input_end')

            @stack('state_input_start')
            @stack('state_input_end')

            @stack('country_input_start')
            @stack('country_input_end')

            @stack('tax_number_input_start')
            @if (! $hideTaxNumber)
            @if ($contact->tax_number)
                <div class="flex flex-col text-sm sm:mb-5">
                    <div class="font-medium">{{ trans('general.tax_number') }}</div>
                    <span>{{ $contact->tax_number }}</span>
                </div>
            @endif
            @endif
            @stack('tax_number_input_end')

            @stack('website_input_start')
            @if (! $hideWebsite)
            @if ($contact->website)
                <div class="flex flex-col text-sm sm:mb-5">
                    <div class="font-medium">{{ trans('general.website') }}</div>
                    <span>{{ $contact->website }}</span>
                </div>
            @endif
            @endif
            @stack('website_input_end')

            @stack('reference_input_start')
            @if (! $hideReference)
            @if ($contact->reference)
                <div class="flex flex-col text-sm sm:mb-5">
                    <div class="font-medium">{{ trans('general.reference') }}</div>
                    <span>{{ $contact->reference }}</span>
                </div>
            @endif
            @endif
            @stack('reference_input_end')

            @stack('create_user_input_start')
                @if (! $hideUser)
                    <div class="flex flex-col text-sm sm:mb-5">
                        <div class="flex items-center font-medium">
                            <div class="flex items-center cursor-default">
                                <x-tooltip id="tooltip-client-describe" placement="bottom" size="w-2/12" message="{{ trans('customers.client_portal_description') }}">
                                    {{ trans('general.client_portal') }}
                                </x-tooltip>

                                @if ($contact->user)
                                    <x-tooltip id="tooltip-client-permission" placement="bottom" message="{{ trans('customers.client_portal_text.can') }}">
                                        <span class="material-icons text-green text-base ltr:ml-1 rtl:mr-1">check</span>
                                    </x-tooltip>
                                @else
                                    <x-tooltip id="tooltip-client-permission" placement="bottom" message="{{ trans('customers.client_portal_text.cant') }}">
                                        <span class="material-icons-round text-red text-sm ltr:ml-1 rtl:mr-1">hide_source</span>
                                    </x-tooltip>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @stack('create_user_input_end')
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
                    />

                    @stack('transactions_nav_start')

                    <x-tabs.nav
                        id="transactions"
                        name="{{ trans_choice('general.transactions', 2) }}"
                    />

                    @stack('transactions_nav_end')
                </x-slot>

                <x-slot name="content">
                    @stack('documents_tab_start')

                    <x-tabs.tab id="documents">
                        @if ($documents->count())
                            <x-table>
                                <x-table.thead>
                                    <x-table.tr>
                                        <x-table.th class="w-4/12 lg:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="due_at" title="{{ trans('invoices.due_date') }}" />
                                            </x-slot>

                                            <x-slot name="second">
                                                <x-sortablelink column="issued_at" title="{{ trans('invoices.invoice_date') }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-3/12" hidden-mobile>
                                            <x-sortablelink column="status" title="{{ trans_choice('general.statuses', 1) }}" />
                                        </x-table.th>

                                        <x-table.th class="w-4/12 lg:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="contact_name" title="{{ trans_choice('general.customers', 1) }}" />
                                            </x-slot>

                                            <x-slot name="second">
                                                <x-sortablelink column="document_number" title="{{ trans_choice('general.numbers', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 lg:w-3/12" kind="amount">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($documents as $item)
                                        @php $paid = $item->paid; @endphp
                                        <x-table.tr href="{{ route(config('type.document.' . $item->type . '.route.prefix', 'invoices') . '.show', $item->id) }}">
                                            <x-table.td class="w-4/12 lg:w-3/12">
                                                <x-slot name="first" class="font-bold truncate" override="class">
                                                    {{ \Date::parse($item->due_at)->diffForHumans() }}
                                                </x-slot>

                                                <x-slot name="second">
                                                    <x-date date="{{ $item->issued_at }}" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-3/12" hidden-mobile>
                                                <x-show.status status="{{ $item->status }}" background-color="bg-{{ $item->status_label }}" text-color="text-text-{{ $item->status_label }}" />
                                            </x-table.td>

                                            <x-table.td class="w-4/12 lg:w-3/12">
                                                <x-slot name="first">
                                                    {{ $item->contact_name }}
                                                </x-slot>

                                                <x-slot name="second" class="w-20 font-normal group" data-tooltip-target="tooltip-information-{{ $item->id }}" data-tooltip-placement="left" override="class,data-tooltip-target,data-tooltip-placement">
                                                    <span class="border-black border-b border-dashed">
                                                        {{ $item->document_number }}
                                                    </span>

                                                    <div class="w-28 absolute h-10 -left-10 -mt-6"></div>

                                                    <x-documents.index.information :document="$item" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 lg:w-3/12" kind="amount">
                                                <x-money :amount="$item->amount" :currency="$item->currency_code" />
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
                                    <x-table.tr>
                                        <x-table.th class="w-4/12 lg:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="number" title="{{ trans_choice('general.numbers', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-3/12" hidden-mobile>
                                            <x-slot name="first">
                                                <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="category.name" title="{{ trans_choice('general.categories', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 lg:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="account.name" title="{{ trans_choice('general.accounts', 1) }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                <x-sortablelink column="document.document_number" title="{{ trans_choice('general.documents', 1) }}" />
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 lg:w-3/12" kind="amount">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($transactions as $item)
                                        <x-table.tr href="{{ route('transactions.show', $item->id) }}">
                                            <x-table.td class="w-4/12 lg:w-3/12">
                                                <x-slot name="first" class="font-bold truncate" override="class">
                                                    <x-date date="{{ $item->paid_at }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    {{ $item->number }}
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-3/12" hidden-mobile>
                                                <x-slot name="first">
                                                    {{ $item->type_title }}
                                                </x-slot>
                                                <x-slot name="second" class="flex items-center">
                                                    <x-index.category :model="$item->category" />
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 lg:w-3/12">
                                                <x-slot name="first">
                                                    {{ $item->account->name }}
                                                </x-slot>
                                                <x-slot name="second" class="w-20 font-normal group" data-tooltip-target="tooltip-information-transaction-{{ $item->id }}" data-tooltip-placement="left" override="class,data-tooltip-target,data-tooltip-placement">
                                                    @if ($item->document)
                                                        <x-link href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed" override="class">
                                                            {{ $item->document->document_number }}
                                                        </x-link>

                                                        <div class="w-28 absolute h-10 -left-10 -mt-6"></div>

                                                        <x-documents.index.information id="tooltip-information-transaction-{{ $item->id }}" :document="$item->document" />
                                                    @else
                                                        <x-empty-data />
                                                    @endif
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 lg:w-3/12" kind="amount">
                                                <x-money :amount="$item->amount" :currency="$item->currency_code" />
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
