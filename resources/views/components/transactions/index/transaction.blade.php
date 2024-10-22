    <x-table>
        <x-table.thead>
            <x-table.tr>
                @if (! $hideBulkAction)
                <x-table.th kind="bulkaction">
                    <x-index.bulkaction.all />
                </x-table.th>
                @endif

                @stack('paid_at_and_number_at_th_start')
                @if (! $hidePaidAt || ! $hideNumber)
                <x-table.th class="{{ $classPaidAtAndNumber }}">
                    @stack('paid_at_th_start')
                    @if (! $hidePaidAt)
                    <x-slot name="first">
                        <x-sortablelink column="paid_at" title="{{ trans($textPaidAt) }}" />
                    </x-slot>
                    @endif
                    @stack('paid_at_th_end')

                    @stack('number_th_start')
                    @if (! $hideNumber)
                    <x-slot name="second">
                        <x-sortablelink column="number" title="{{ trans_choice($textNumber, 1) }}" />
                    </x-slot>
                    @endif
                    @stack('number_th_end')
                </x-table.th>
                @endif
                @stack('paid_at_and_number_at_th_end')

                @stack('type_and_category_th_start')
                @if (! $hideType || ! $hideCategory)
                <x-table.th class="{{ $classTypeAndCategory }}" hidden-mobile>
                    @stack('type_th_start')
                    @if (! $hideType)
                    <x-slot name="first">
                        <x-sortablelink column="type" title="{{ trans_choice($textType, 1) }}" />
                    </x-slot>
                    @endif
                    @stack('type_th_end')

                    @stack('category_th_start')
                    @if (! $hideCategory)
                    <x-slot name="second">
                        <x-sortablelink column="category.name" title="{{ trans_choice($textCategory, 1) }}" />
                    </x-slot>
                    @endif
                    @stack('category_th_end')
                </x-table.th>
                @endif
                @stack('type_and_category_th_end')

                @stack('account_th_start')
                @if (! $hideAccount)
                <x-table.th class="{{ $classAccount }}">
                    @stack('account_inside_th_start')
                    <x-sortablelink column="account.name" title="{{ trans_choice($textAccount, 1) }}" />
                    @stack('account_inside_th_end')
                </x-table.th>
                @endif
                @stack('account_th_end')

                @stack('contact_and_document_th_start')
                @if (! $hideContact || ! $hideDocument)
                <x-table.th class="{{ $classContactAndDocument }}" hidden-mobile>
                    @stack('contact_th_start')
                    @if (! $hideContact)
                    <x-slot name="first">
                        <x-sortablelink column="contact.name" title="{{ trans_choice($textContact, 1) }}" />
                    </x-slot>
                    @endif
                    @stack('contact_th_end')

                    @stack('document_th_start')
                    @if (! $hideDocument)
                    <x-slot name="second">
                        <x-sortablelink column="document.document_number" title="{{ trans_choice($textDocument, 1) }}" />
                    </x-slot>
                    @endif
                    @stack('document_th_end')
                </x-table.th>
                @endif
                @stack('contact_and_document_th_end')

                @stack('amount_th_start')
                @if (! $hideAmount)
                <x-table.th class="{{ $classAmount }}" kind="amount">
                    @stack('amount_inside_th_start')
                    <x-sortablelink column="amount" title="{{ trans($textAmount) }}" />
                    @stack('amount_inside_th_end')
                </x-table.th>
                @endif
                @stack('amount_th_end')
            </x-table.tr>
        </x-table.thead>

        <x-table.tbody>
            @foreach($transactions as $item)
                <x-table.tr href="{{ route('transactions.show', $item->id) }}">
                    @if (! $hideBulkAction)
                    <x-table.td kind="bulkaction">
                        <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->contact->name }}" />
                    </x-table.td>
                    @endif

                    @stack('paid_at_and_number_at_td_start')
                    @if (! $hidePaidAt || ! $hideNumber)
                    <x-table.td class="{{ $classPaidAtAndNumber }}">
                        @stack('paid_at_td_start')
                        @if (! $hidePaidAt)
                        <x-slot name="first" class="font-bold truncate" override="class">
                            <x-date date="{{ $item->paid_at }}" />
                        </x-slot>
                        @endif
                        @stack('paid_at_td_end')

                        @stack('number_td_start')
                        @if (! $hideNumber)
                        <x-slot name="second">
                            {{ $item->number }}
                        </x-slot>
                        @endif
                        @stack('number_td_end')
                    </x-table.td>
                    @endif
                    @stack('paid_at_and_number_at_td_end')

                    @stack('type_and_category_td_start')
                    @if (! $hideType || ! $hideCategory)
                    <x-table.td class="{{ $classTypeAndCategory }}" hidden-mobile>
                        @stack('type_td_start')
                        @if (! $hideType)
                        <x-slot name="first">
                            {{ $item->type_title }}
                        </x-slot>
                        @endif
                        @stack('type_td_end')

                        @stack('category_td_start')
                        @if (! $hideCategory)
                        <x-slot name="second" class="flex items-center">
                            <x-index.category :model="$item->category" />
                        </x-slot>
                        @endif
                        @stack('category_td_end')
                    </x-table.td>
                    @endif
                    @stack('type_and_category_td_end')

                    @stack('account_td_start')
                    @if (! $hideAccount)
                    <x-table.td class="{{ $classAccount }}">
                        @stack('account_td_inside_start')
                        {{ $item->account->name }}
                        @stack('account_td_inside_end')
                    </x-table.td>
                    @endif
                    @stack('account_td_end')

                    @stack('contact_and_document_td_start')
                    @if (! $hideContact || ! $hideDocument)
                    <x-table.td class="{{ $classContactAndDocument }}" hidden-mobile>
                        @stack('contact_td_start')
                        @if (! $hideContact)
                        <x-slot name="first">
                            {{ $item->contact->name }}
                        </x-slot>
                        @endif
                        @stack('contact_td_end')

                        @stack('document_td_start')
                        @if (! $hideDocument)
                        <x-slot name="second" class="w-20 font-normal group">
                            @if ($item->document)
                                <div data-tooltip-target="tooltip-information-{{ $item->document_id }}" data-tooltip-placement="left" override="class">
                                    <x-link href="{{ route($item->route_name, $item->route_id) }}" class="font-normal truncate border-b border-black border-dashed" override="class">
                                        {{ $item->document->document_number }}
                                    </x-link>

                                    <div class="w-28 absolute h-10 -ml-12 -mt-6">
                                    </div>

                                    <x-documents.index.information :document="$item->document" />
                                </div>
                            @else
                                <x-empty-data />
                            @endif
                        </x-slot>
                        @endif
                        @stack('document_td_end')
                    </x-table.td>
                    @endif
                    @stack('contact_and_document_td_end')

                    @stack('amount_td_start')
                    @if (! $hideAmount)
                    <x-table.td class="{{ $classAmount }}" kind="amount">
                        @stack('amount_td_inside_start')
                        <x-money :amount="$item->amount" :currency="$item->currency_code" />
                        @stack('amount_td_inside_end')
                    </x-table.td>
                    @endif
                    @stack('amount_td_end')

                    <x-table.td kind="action">
                        <x-table.actions :model="$item" />
                    </x-table.td>
                </x-table.tr>
            @endforeach
        </x-table.tbody>
    </x-table>

    <x-pagination :items="$transactions" />
    