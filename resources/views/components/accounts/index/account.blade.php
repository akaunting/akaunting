{{--
 | One account: what it is at the top, then what it holds on the left and how its balance has
 | moved on the right, split by a line the way a statement separates its header from its figures.
 --}}
{{-- A disabled account looks like any other: the mark beside its name says it is disabled,
     the way the other lists say it --}}
<x-index.card
    :model="$account"
    :hide-bulk-action="$hideBulkAction"
    body-class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center mt-4 pt-5 border-t border-gray-200"
>
    <x-slot name="title">
        {{-- The name and the number are what an account is told apart by, so the name leads
             the card and is the one way into the account: the rest carries actions of its own --}}
        <x-link
            href="{{ route('accounts.show', $account->id) }}"
            class="pointer-events-auto truncate text-base font-bold text-purple hover:text-purple-700 hover:underline"
            override="class"
            id="index-account-show-{{ $account->id }}"
        >
            {{ $account->name }}
        </x-link>

        {{-- The marks explain themselves on hover, so they take the pointer back from the title
             they sit in --}}
        @if (! $account->enabled)
            <span class="pointer-events-auto">
                <x-index.disable text="{{ trans_choice('general.accounts', 1) }}" />
            </span>
        @endif

        @if ($isDefault)
            <span class="pointer-events-auto">
                <x-index.default text="{{ trans('accounts.default_account') }}" />
            </span>
        @endif
    </x-slot>

    <x-slot name="subtitle">
        @if (! empty($account->number))
            <span class="block mt-0.5 text-sm font-medium text-black">
                {{ $account->number }}
            </span>
        @else
            <x-empty-data />
        @endif
    </x-slot>

    <x-slot name="actions">
        <div class="flex items-center">
            {{-- The bank behind the account: its mark when an app knows it, its name otherwise --}}
            @if ($appLogo !== '')
                {!! $appLogo !!}
            @elseif (! empty($account->bank_name))
                {{-- Given the height a bank's mark takes, so every card's corner lines up the same --}}
                <div class="flex items-center h-10 ltr:mr-6 rtl:ml-6">
                    <x-tooltip id="tooltip-account-bank-{{ $account->id }}" placement="top" whitespace="whitespace-nowrap" message="{{ $account->bank_name }}">
                        <span class="block max-w-32 text-sm text-black-400 truncate">
                            {{ $account->bank_name }}
                        </span>
                    </x-tooltip>
                </div>
            @endif

            <x-index.card.actions :model="$account" id="index-line-actions-account-{{ $account->id }}" />
        </div>
    </x-slot>

    {{-- Every card is as tall as one with a button, a line about its feed and its balance;
         a card with less in it spreads it evenly over that height rather than crowding it --}}
    <div class="flex flex-col justify-evenly gap-4 min-w-0 min-h-20">
        {{-- Reconciling stays on every card: an account an app keeps up to date can have
             transactions entered by hand as well. Every action is disabled once clicked so it is
             not sent twice: the green button shows the loading dots, which are white and would
             not show on the others, so those fade --}}
        @if ($hasActions)
            <div class="flex flex-wrap items-center gap-x-4 gap-y-4 leading-none">
                {!! $appPrimary !!}

                {{-- A reconciliation left as a draft is carried on with rather than started again
                     beside it, which would cover the same days twice --}}
                @if (! empty($reconciliation['draft_id']))
                    @can('update-banking-reconciliations')
                        <x-link
                            href="{{ route('reconciliations.edit', $reconciliation['draft_id']) }}"
                            class="{{ $reconcileClass }}"
                            override="class"
                            id="index-account-continue-reconciliation-{{ $account->id }}"
                            @click="e => e.currentTarget.classList.add('disabled-link')"
                        >
                            @if ($appPrimary === '')
                                <x-link.loading>
                                    {{ trans('accounts.continue_reconciliation') }}
                                </x-link.loading>
                            @else
                                {{ trans('accounts.continue_reconciliation') }}
                            @endif
                        </x-link>
                    @else
                        <span class="text-sm font-medium text-orange">
                            {{ trans('reconciliations.in_progress') }}
                        </span>
                    @endcan
                @elseif (! empty($reconciliation['count']) && ! empty($reconciliation['started_at']))
                    @can('create-banking-reconciliations')
                        <x-link
                            href="{{ route('reconciliations.create', [
                                'account_id'    => $account->id,
                                'started_at'    => $reconciliation['started_at']->toDateString(),
                                'ended_at'      => $reconciliation['ended_at']->toDateString(),
                            ]) }}"
                            class="{{ $reconcileClass }}"
                            override="class"
                            id="index-account-reconcile-{{ $account->id }}"
                            @click="e => e.currentTarget.classList.add('disabled-link')"
                        >
                            @if ($appPrimary === '')
                                <x-link.loading>
                                    {{ trans_choice('accounts.reconcile_items', $reconciliation['count'], ['count' => $reconciliation['count']]) }}
                                </x-link.loading>
                            @else
                                {{ trans_choice('accounts.reconcile_items', $reconciliation['count'], ['count' => $reconciliation['count']]) }}
                            @endif
                        </x-link>
                    @else
                        <span class="text-sm font-medium text-orange">
                            {{ trans_choice('accounts.reconcile_items', $reconciliation['count'], ['count' => $reconciliation['count']]) }}
                        </span>
                    @endcan
                @elseif (! empty($statement))
                    <span class="flex items-center text-sm font-medium leading-none text-green">
                        <span class="material-icons-outlined text-lg leading-none ltr:mr-1 rtl:ml-1" aria-hidden="true">check_circle</span>

                        {{ trans('reconciliations.reconciled') }}
                    </span>
                @endif

                {!! $appLinks !!}

                {{-- An account an app keeps up to date gets its transactions from it, so a
                     statement imported beside them would add each one twice --}}
                @if ($appStatus === '')
                    @can('create-banking-transactions')
                        <x-link
                            href="{{ route('accounts.import-transactions', $account->id) }}"
                            class="{{ $linkClass }}"
                            override="class"
                            id="index-account-import-{{ $account->id }}"
                            @click="e => e.currentTarget.classList.add('disabled-link')"
                        >
                            {{ trans('accounts.import_statement') }}
                        </x-link>
                    @endcan
                @endif

                {{-- Added to a period already reconciled: said apart, since the next
                     reconciliation does not reach back to it --}}
                @if (! empty($reconciliation['earlier']))
                    <x-tooltip
                        id="tooltip-account-earlier-{{ $account->id }}"
                        message="{{ trans('accounts.earlier_unreconciled_description', ['date' => company_date($statement['date'])]) }}"
                        placement="top"
                        size="w-64"
                    >
                        <span
                            id="index-account-earlier-{{ $account->id }}"
                            class="relative pointer-events-auto inline-flex items-center gap-1 text-sm font-medium leading-none text-orange cursor-default"
                        >
                            <span class="material-icons-outlined text-base leading-none" aria-hidden="true">history</span>

                            {{ trans_choice('accounts.earlier_unreconciled', $reconciliation['earlier'], ['count' => $reconciliation['earlier']]) }}
                        </span>
                    </x-tooltip>
                @endif
            </div>
        @endif

        {!! $appStatus !!}

        <dl class="grid grid-cols-2 gap-y-1 text-sm">
            <dt class="text-black-400">
                {{ trans('accounts.current_balance') }}
            </dt>

            <dd @class(['ltr:text-right rtl:text-left font-bold', 'text-red' => $isOverdrawn])>
                <x-money :amount="$account->balance" :currency="$account->currency_code" />
            </dd>

            {{-- Hidden until Akaunting keeps the bank's own statement balance; the last
                 reconciliation is all it has to show here for now --}}
            <dt class="hidden text-black-400">
                {{ trans('accounts.statement_balance') }}

                @if (! empty($statement['date']))
                    <span class="text-xs">({{ company_date($statement['date']) }})</span>
                @endif
            </dt>

            <dd class="hidden ltr:text-right rtl:text-left font-bold">
                @if (! empty($statement))
                    <x-money :amount="$statement['balance']" :currency="$account->currency_code" />
                @else
                    <x-empty-data />
                @endif
            </dd>
        </dl>
    </div>

    @if ($chart)
        {{-- Drawn with the charts of the dashboard; hovered, so it takes the pointer back from
             the card it sits in --}}
        <figure
            class="flex flex-col self-stretch min-w-0 pointer-events-auto"
            aria-label="{{ trans('accounts.balance_history_label', [
                'account'   => $account->name,
                'days'      => count($history),
                'start'     => company_date($historyStart),
                'end'       => company_date($historyEnd),
            ]) }}"
        >
            {{-- Beside the figures the chart takes their height and adds none of its own, so the
                 room above, between and below them stays the same on every card, whatever it
                 shows; under them, on a phone, it has a height of its own --}}
            <div class="relative grow h-14 lg:h-auto">
                <div class="absolute inset-0">
                    {!! $chart->container() !!}
                </div>
            </div>

            <figcaption class="flex justify-between mt-1 text-xs text-black-400">
                <span>{{ $historyStart->translatedFormat('j M') }}</span>
                <span>{{ trans('accounts.balance_history', ['days' => count($history)]) }}</span>
                <span>{{ $historyEnd->translatedFormat('j M') }}</span>
            </figcaption>
        </figure>

        @push('body_scripts')
            {!! $chart->script() !!}
        @endpush
    @endif
</x-index.card>
