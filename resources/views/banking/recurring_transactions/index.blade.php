<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.recurring_templates', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.recurring_templates', 2) }}"
        icon="receipt_long"
        route="recurring-transactions.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-transactions')
            <x-link href="{{ route('recurring-transactions.create', ['type' => 'income-recurring']) }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.recurring_incomes', 1)]) }}
            </x-link>

            <x-link href="{{ route('recurring-transactions.create', ['type' => 'expense-recurring']) }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.recurring_expenses', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($transactions->count() || request()->get('search', false))
            <x-index.container>
                <x-tabs active="recurring-templates">
                    <x-slot name="navs">
                        <x-tabs.nav-link id="transactions" name="{{ trans_choice('general.transactions', 2) }}" href="{{ route('transactions.index') }}" />
                        <x-tabs.nav id="recurring-templates" name="{{ trans_choice('general.recurring_templates', 2) }}" active />
                    </x-slot>

                    <x-slot name="content">
                        <x-index.search
                            search-string="App\Models\Banking\Transaction"
                        />

                        <x-tabs.tab id="recurring-templates">
                            <x-table>
                                <x-table.thead>
                                    <x-table.tr class="flex items-center px-1">
                                        <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                            <x-index.bulkaction.all />
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-3/12">
                                            <x-slot name="first">
                                                <x-sortablelink column="recurring.started_at" title="{{ trans('general.start_date') }}" />
                                            </x-slot>
                                            <x-slot name="second">
                                                {{ trans('recurring.last_issued') }}
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
                                            <x-sortablelink column="recurring.status" title="{{ trans_choice('general.statuses', 1) }}" />
                                        </x-table.th>

                                        <x-table.th class="w-2/12 hidden sm:table-cell">
                                            <x-slot name="first">
                                                {{ trans('recurring.frequency') }}
                                            </x-slot>
                                            <x-slot name="second">
                                                {{ trans('recurring.duration') }}
                                            </x-slot>
                                        </x-table.th>

                                        <x-table.th class="w-4/12 sm:w-2/12" kind="amount">
                                            <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                                        </x-table.th>
                                    </x-table.tr>
                                </x-table.thead>

                                <x-table.tbody>
                                    @foreach($transactions as $item)
                                        <x-table.tr href="{{ route('recurring-transactions.show', $item->id) }}">
                                            <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                                <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->contact->name }}" />
                                            </x-table.td>

                                            <x-table.td class="w-4/12 sm:w-3/12">
                                                <x-slot name="first">
                                                    <x-date date="{{ $item->recurring->started_at }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    @if ($last = $item->recurring->getLastRecurring())
                                                        {{ $last->format(company_date_format()) }}
                                                    @endif
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
                                                <x-index.status status="{{ $item->recurring->status }}" background-color="bg-{{ $item->recurring_status_label }}" text-color="text-text-{{ $item->recurring_status_label }}" />
                                            </x-table.td>

                                            <x-table.td class="w-2/12 hidden sm:table-cell">
                                                <x-slot name="first">
                                                    {{ trans('recurring.' . $item->recurring->frequency) }}
                                                </x-slot>
                                                <x-slot name="second">
                                                    @if ($item->recurring->limit_by == 'count')
                                                        @if ($item->recurring->limit_count == 0)
                                                            {{ trans('recurring.ends_never') }}
                                                        @else
                                                            {{ trans('recurring.ends_after', ['times' => $item->recurring->limit_count]) }}
                                                        @endif
                                                    @else
                                                        {{ trans('recurring.ends_date', ['date' => company_date($item->recurring->limit_date)]) }}
                                                    @endif
                                                </x-slot>
                                            </x-table.td>

                                            <x-table.td class="w-4/12 sm:w-2/12" kind="amount">
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
                        </x-tabs.tab>
                    </x-slot>
                </x-tabs>
            </x-index.container>
        @else
            <x-empty-page
                group="banking"
                page="recurring_templates"
                hide-button-import
                permission-create="create-banking-transactions"
                :buttons="[
                    [
                        'url' =>  route('recurring-transactions.create', ['type' => 'income-recurring']),
                        'permission' => 'create-banking-transactions',
                        'text' => trans('general.title.new', ['type' =>  trans_choice('general.recurring_incomes', 1)]),
                        'description' => '',
                        'active_badge' => true,
                    ],
                    [
                        'url' => route('recurring-transactions.create', ['type' => 'expense-recurring']),
                        'permission' => 'create-banking-transactions',
                        'text' => trans('general.title.new', ['type' =>  trans_choice('general.recurring_expenses', 1)]),
                        'description' => '',
                        'active_badge' => true,
                    ],
                ]"
            />
        @endif
    </x-slot>

    <x-script folder="banking" file="transactions" />
</x-layouts.admin>
