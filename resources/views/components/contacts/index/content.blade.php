@if ($hideEmptyPage || ($contacts->count() || request()->get('search', false)))
    @if (! $hideSummary)
    <x-index.summary :items="$summaryItems" />
    @endif

    <x-index.container>
        @if ((! $hideSearchString) && (! $hideBulkAction))
        <x-index.search
            search-string="{{ $searchStringModel }}"
            bulk-action="{{ $bulkActionClass }}"
            route="{{ $searchRoute }}"
        />
        @elseif ((! $hideSearchString) && $hideBulkAction)
        <x-index.search
            search-string="{{ $searchStringModel }}"
            route="{{ $searchRoute }}"
        />
        @elseif ($hideSearchString && (! $hideBulkAction))
        <x-index.search
            bulk-action="{{ $bulkActionClass }}"
            route="{{ $searchRoute }}"
        />
        @endif

        <x-table>
            <x-table.thead>
                <x-table.tr>
                    @if (! $hideBulkAction)
                    <x-table.th class="{{ $classBulkAction }}" override="class">
                        <x-index.bulkaction.all />
                    </x-table.th>
                    @endif

                    @stack('name_and_tax_number_th_start')
                    @if ((! $hideName) || (! $hideTaxNumber))
                    <x-table.th class="{{ $classNameAndTaxNumber }}">
                        @stack('name_th_start')
                        @if (! $hideName)
                        <x-slot name="first">
                            <x-sortablelink column="name" title="{{ trans($textName) }}" />
                        </x-slot>
                        @endif
                        @stack('name_th_end')

                        @stack('tax_number_th_start')
                        @if (! $hideTaxNumber)
                        <x-slot name="second">
                            <x-sortablelink column="tax_number" title="{{ trans($textTaxNumber) }}" />
                        </x-slot>
                        @endif
                        @stack('tax_number_th_end')
                    </x-table.th>
                    @endif
                    @stack('name_and_tax_number_th_end')

                    @stack('email_and_phone_th_start')
                    @if ((! $hideEmail) || (! $hidePhone))
                    <x-table.th class="{{ $classEmailAndPhone }}">
                        @stack('email_th_start')
                        @if (! $hideEmail)
                        <x-slot name="first">
                            <x-sortablelink column="email" title="{{ trans($textEmail) }}" />
                        </x-slot>
                        @endif
                        @stack('email_th_end')

                        @stack('phone_th_start')
                        @if (! $hidePhone)
                        <x-slot name="second">
                            <x-sortablelink column="phone" title="{{ trans($textPhone) }}" />
                        </x-slot>
                        @endif
                        @stack('phone_th_end')
                    </x-table.th>
                    @endif
                    @stack('email_and_phone_th_end')

                    @stack('country_and_currency_code_th_start')
                    @if ((! $hideCountry) || (! $hideCurrencyCode))
                    <x-table.th class="{{ $classCountryAndCurrencyCode }}">
                        @stack('country_th_start')
                        @if (! $hideCountry)
                        <x-slot name="first">
                            <x-sortablelink column="country" title="{{ trans_choice($textCountry, 1) }}" />
                        </x-slot>
                        @endif
                        @stack('country_th_end')

                        @stack('currency_code_th_start')
                        @if (! $hideCurrencyCode)
                        <x-slot name="second">
                            <x-sortablelink column="currency_code" title="{{ trans_choice($textCurrencyCode, 1) }}" />
                        </x-slot>
                        @endif
                        @stack('currency_code_th_end')
                    </x-table.th>
                    @endif
                    @stack('country_and_currency_code_th_end')

                    @stack('open_and_overdue_th_start')
                    @if ((! $hideOpen) || (! $hideOverdue))
                    <x-table.th class="{{ $classOpenAndOverdue }}" kind="amount">
                        @stack('open_th_start')
                        @if (! $hideOpen)
                        <x-slot name="first">
                            {{ trans($textOpen) }}
                        </x-slot>
                        @endif
                        @stack('open_th_end')

                        @stack('overdue_th_start')
                        @if (! $hideOverdue)
                        <x-slot name="second">
                            {{ trans($textOverdue) }}
                        </x-slot>
                        @endif
                        @stack('overdue_th_end')
                    </x-table.th>
                    @endif
                    @stack('open_and_overdue_th_end')
                </x-table.tr>
            </x-table.thead>

            <x-table.tbody>
                @foreach($contacts as $item)
                    <x-table.tr href="{{ route($routeButtonShow, $item->id) }}">
                        @if (! $hideBulkAction)
                        <x-table.td class="{{ $classBulkAction }}" override="class">
                            <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                        </x-table.td>
                        @endif

                        @stack('name_and_tax_number_td_start')
                        @if ((! $hideName) || (! $hideTaxNumber))
                        <x-table.td class="{{ $classNameAndTaxNumber }}">
                            @stack('name_td_start')
                            @if (! $hideName)
                            <x-slot name="first" class="flex items-center">
                                @if ($showLogo)
                                    @if (is_object($item->logo))
                                        <img src="{{ Storage::url($item->logo->id) }}" class="absolute w-6 h-6 bottom-6 rounded-full  ltr:mr-3 rtl:ml-3 hidden lg:block" alt="{{ $item->name }}" title="{{ $item->name }}">
                                    @else
                                        <img src="{{ asset('public/img/user.svg') }}" class="absolute w-6 h-6 bottom-6 rounded-full  ltr:mr-3 rtl:ml-3 hidden lg:block" alt="{{ $item->name }}"/>
                                    @endif
                                @endif

                                <div class="font-bold truncate {{ $showLogo ? 'ltr:lg:pl-8 rtl:lg:pr-8' : '' }}">
                                    {{ $item->name }}
                                </div>

                                @if (! $item->enabled)
                                    <x-index.disable text="{{ trans_choice($textPage, 1) }}" />
                                @endif
                            </x-slot>
                            @endif
                            @stack('name_td_end')

                            @stack('tax_number_td_start')
                            @if (! $hideTaxNumber)
                            <x-slot name="second" class="w-32 {{ $showLogo ? ' ltr:pl-8 rtl:pr-8' : '' }}">
                                {{ $item->tax_number }}
                            </x-slot>
                            @endif
                            @stack('tax_number_td_end')
                        </x-table.td>
                        @endif
                        @stack('name_and_tax_number_td_end')

                        @stack('email_and_phone_td_start')
                        @if ((! $hideEmail) || (! $hidePhone))
                        <x-table.td class="{{ $classEmailAndPhone }}">
                            @stack('email_td_start')
                            @if (! $hideEmail)
                            <x-slot name="first">
                                @if ($item->email)
                                    {{ $item->email }}
                                @else
                                    <x-empty-data />
                                @endif
                            </x-slot>
                            @endif
                            @stack('email_td_end')

                            @stack('phone_td_start')
                            @if (! $hidePhone)
                            <x-slot name="second">
                                {{ $item->phone }}
                            </x-slot>
                            @endif
                            @stack('phone_td_end')
                        </x-table.td>
                        @endif
                        @stack('email_and_phone_td_end')

                        @stack('country_and_currency_code_td_start')
                        @if ((! $hideCountry) || (! $hideCurrencyCode))
                        <x-table.td class="{{ $classCountryAndCurrencyCode }}">
                            @stack('country_td_start')
                            @if (! $hideCountry)
                            <x-slot name="first">
                                <x-index.country code="{{ $item->country }}" />
                            </x-slot>
                            @endif
                            @stack('country_td_end')

                            @stack('currency_code_td_start')
                            @if (! $hideCurrencyCode)
                            <x-slot name="second">
                                <x-index.currency code="{{ $item->currency_code }}" />
                            </x-slot>
                            @endif
                            @stack('currency_code_td_end')
                        </x-table.td>
                        @endif
                        @stack('country_and_currency_code_td_end')

                        @stack('open_and_overdue_td_start')
                        @if ((! $hideOpen) || (! $hideOverdue))
                        <x-table.td class="{{ $classOpenAndOverdue }}" kind="amount">
                            @stack('open_td_start')
                            @if (! $hideOpen)
                            <x-slot name="first">
                                @if ($item->open)
                                    <x-money :amount="$item->open" />
                                @else
                                    <x-empty-data />
                                @endif
                            </x-slot>
                            @endif
                            @stack('open_td_end')

                            @stack('overdue_td_start')
                            @if (! $hideOverdue)
                            <x-slot name="second">
                                @if ($item->overdue)
                                    <x-money :amount="$item->overdue" />
                                @else
                                    <x-empty-data />
                                @endif
                            </x-slot>
                            @endif
                            @stack('overdue_td_end')
                        </x-table.td>
                        @endif
                        @stack('open_and_overdue_td_end')

                        <x-table.td kind="action">
                            <x-table.actions :model="$item" />
                        </x-table.td>
                    </x-table.tr>
                @endforeach
            </x-table.tbody>
        </x-table>

        <x-pagination :items="$contacts" />
    </x-index.container>
@else
    <x-empty-page
        group="{{ $group }}"
        page="{{ $page }}"
        image-empty-page="{{ $imageEmptyPage }}"
        text-empty-page="{{ $textEmptyPage }}"
        url-docs-path="{{ $urlDocsPath }}"
        create-route="{{ $createRoute }}"
        check-permission-create="{{ $checkPermissionCreate }}"
        permission-create="{{ $permissionCreate }}"
    />
@endif
