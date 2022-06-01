<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.reconciliations', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.reconciliations', 2) }}"
        icon="checklist_rtl"
        route="reconciliations.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-reconciliations')
            <x-link href="{{ route('reconciliations.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.reconciliations', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($reconciliations->count() || request()->get('search', false))
            <x-index.summary>
                <x-slot name="first"
                    href="{{ route('reconciliations.index', ['search' => 'reconciled:1']) }}"
                    amount="{{ money($reconciliations->where('reconciled', 1)->sum('closing_balance'), setting('default.currency'), true) }}"
                    title="{{ trans('reconciliations.reconciled_amount') }}"
                ></x-slot>

                <x-slot name="second"
                    href="{{ route('reconciliations.index', ['search' => 'reconciled:0']) }}"
                    amount="{{ money($reconciliations->where('reconciled', 0)->sum('closing_balance'), setting('default.currency'), true) }}"
                    title="{{ trans('reconciliations.in_progress') }}"
                ></x-slot>
            </x-index.summary>

            <x-index.container>
                <x-index.search
                    search-string="App\Models\Banking\Reconciliation"
                    bulk-action="App\BulkActions\Banking\Reconciliations"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                <x-index.bulkaction.all />
                            </x-table.th>

                            <x-table.th class="w-3/12 hidden sm:table-cell">
                                <x-sortablelink column="created_at" title="{{ trans('general.created_date') }}" />
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-3/12">
                                <x-sortablelink column="account_id" title="{{ trans_choice('general.accounts', 1) }}" />
                            </x-table.th>

                            <x-table.th class="w-2/12 hidden sm:table-cell">
                                {{ trans('general.period') }}
                            </x-table.th>

                            <x-table.th class="w-6/12 sm:w-4/12" kind="amount">
                                <x-slot name="first">
                                    <x-sortablelink column="opening_balance" title="{{ trans('reconciliations.opening_balance') }}" />
                                </x-slot>
                                <x-slot name="second">
                                    <x-sortablelink column="closing_balance" title="{{ trans('reconciliations.closing_balance') }}" />
                                </x-slot>
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($reconciliations as $item)
                            <x-table.tr href="{{ route('reconciliations.edit', $item->id) }}">
                                <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->account->name }}" />
                                </x-table.td>

                                <x-table.td class="w-3/12 hidden sm:table-cell">
                                    <x-slot name="first" class="flex" override="class">
                                        <div class="font-bold truncate">
                                            <x-date date="{{ $item->created_at }}" />
                                        </div>

                                        @if (! $item->reconciled)
                                            <x-index.disable text="{{ trans('reconciliations.in_progress') }}" />
                                        @endif
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-3/12 truncate">
                                    {{ $item->account->name }}
                                </x-table.td>

                                <x-table.td class="w-3/12 hidden sm:table-cell">
                                    <x-slot name="first">
                                        <x-date date="{{ $item->started_at }}" />
                                    </x-slot>
                                    <x-slot name="second">
                                        <x-date date="{{ $item->ended_at }}" />
                                    </x-slot>
                                </x-table.td>

                                <x-table.td class="w-6/12 sm:w-3/12" kind="amount">
                                    @if ($item->closing_balance)
                                        <x-money :amount="$item->closing_balance" :currency="$item->account->currency_code" convert />
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$reconciliations" />
            </x-index.container>
        @else
            <x-empty-page group="banking" page="reconciliations" />
        @endif
    </x-slot>

    <x-script folder="banking" file="reconciliations" />
</x-layouts.admin>
