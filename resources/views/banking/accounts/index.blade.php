<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.accounts', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.accounts', 2) }}"
        icon="account_balance"
        route="accounts.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-banking-accounts')
            <x-link href="{{ route('accounts.create') }}" kind="primary" id="index-more-actions-new-account">
                {{ trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="App\Models\Banking\Account"
                bulk-action="App\BulkActions\Banking\Accounts"
            />

            <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th kind="bulkaction">
                            <x-index.bulkaction.all />
                        </x-table.th>

                        <x-table.th class="w-6/12 sm:w-5/12">
                            <x-slot name="first">
                                <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                            </x-slot>
                            <x-slot name="second">
                                <x-sortablelink column="number" title="{{ trans('accounts.number') }}" />
                            </x-slot>
                        </x-table.th>

                        <x-table.th class="w-4/12" hidden-mobile>
                            <x-slot name="first">
                                <x-sortablelink column="bank_name" title="{{ trans('accounts.bank_name') }}" />
                            </x-slot>
                            <x-slot name="second">
                                <x-sortablelink column="bank_phone" title="{{ trans('general.phone') }}" />
                            </x-slot>
                        </x-table.th>

                        <x-table.th class="w-6/12 sm:w-3/12" kind="amount">
                            <x-sortablelink column="balance" title="{{ trans('accounts.current_balance') }}" />
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($accounts as $item)
                        <x-table.tr href="{{ route('accounts.show', $item->id) }}">
                            <x-table.td kind="bulkaction">
                                <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                            </x-table.td>

                            <x-table.td class="w-6/12 sm:w-5/12">
                                <x-slot name="first" class="flex font-bold">
                                    {{ $item->name }}

                                    @if (! $item->enabled)
                                        <x-index.disable text="{{ trans_choice('general.accounts', 1) }}" />
                                    @endif

                                    @if (setting('default.account') == $item->id)
                                        <x-index.default text="{{ trans('accounts.default_account') }}" />
                                    @endif
                                </x-slot>
                                <x-slot name="second" class="font-normal truncate">
                                    {{ $item->number }}
                                </x-slot>
                            </x-table.td>

                            <x-table.td class="w-4/12" hidden-mobile>
                                <x-slot name="first">
                                    @if (! empty($item->bank_name))
                                        {{ $item->bank_name }}
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                                <x-slot name="second">
                                    @if (! empty($item->phone))
                                        {{ $item->phone }}
                                    @else
                                        <x-empty-data />
                                    @endif
                                </x-slot>
                            </x-table.td>

                            <x-table.td class="w-6/12 sm:w-3/12" kind="amount">
                                <x-money :amount="$item->balance" :currency="$item->currency_code" />
                            </x-table.td>

                            <x-table.td kind="action">
                                <x-table.actions :model="$item" />
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>

            <x-pagination :items="$accounts" />
        </x-index.container>
    </x-slot>

    <x-script folder="banking" file="accounts" />
</x-layouts.admin>
