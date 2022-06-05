@stack('button_group_start')

@if (! $hideButtonMoreActions)
    <x-dropdown id="dropdown-more-actions">
        <x-slot name="trigger">
            <span class="material-icons">more_horiz</span>
        </x-slot>

        @stack('duplicate_button_start')

        @if (! $transaction->hasTransferRelation)
            @if (! $hideButtonDuplicate)
                @can($permissionCreate)
                    <x-dropdown.link href="{{ route($routeButtonDuplicate, [$transaction->id, 'type' => $type]) }}">
                        {{ trans('general.duplicate') }}
                    </x-dropdown.link>
                @endcan
            @endif
        @endif

        @stack('duplicate_button_end')

        @stack('connect_button_start')

        @if ($transaction->is_splittable && empty($transaction->document_id) && empty($transaction->recurring))
            @if (! $hideButtonConnect)
                @can($permissionCreate)
                    <button
                        type="button"
                        class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap"
                        title="{{ trans('general.connect') }}"
                        @click="onConnect('{{ route('transactions.dial', $transaction->id) }}')">
                        <span class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100">{{ trans('general.connect') }}</span>
                    </button>
                @endcan
            @endif
        @endif

        @stack('connect_button_end')

        @if (! $hideDivider1)
            <x-dropdown.divider />
        @endif

        @stack('button_print_start')

        @if (! $hideButtonPrint)
            <x-dropdown.link href="{{ route($routeButtonPrint, $transaction->id) }}" target="_blank">
                {{ trans('general.print') }}
            </x-dropdown.link>
        @endif

        @stack('button_print_end')

        @stack('button_pdf_start')

        @if (! $hideButtonPdf)
            <x-dropdown.link href="{{ route($routeButtonPdf, $transaction->id) }}" class="">
                {{ trans('general.download_pdf') }}
            </x-dropdown.link>
        @endif

        @stack('button_pdf_end')

        @if (! $hideDivider2)
            <x-dropdown.divider />
        @endif

        @stack('share_button_start')

        @if (! $transaction->hasTransferRelation)
            @if (! $hideButtonShare)
                <x-dropdown.button @click="onShareLink('{{ route($shareRoute, $transaction->id) }}')">
                    {{ trans('general.share_link') }}
                </x-dropdown.button>
            @endif
        @endif

        @stack('share_button_end')

        @stack('email_button_start')

        @if (! $transaction->hasTransferRelation)
            @if (! $hideButtonEmail)
                @if (! empty($transaction->contact) && $transaction->contact->email)
                    <x-dropdown.button @click="onEmail('{{ route($routeButtonEmail, $transaction->id) }}')">
                        {{ trans('invoices.send_mail') }}
                    </x-dropdown.button>
                @else
                    <x-tooltip message="{{ trans('invoices.messages.email_required') }}" placement="left">
                        <x-dropdown.button disabled="disabled">
                            {{ trans('invoices.send_mail') }}
                        </x-dropdown.button>
                    </x-tooltip>
                @endif
            @endif
        @endif

        @stack('email_button_end')

        @if (! $hideDivider3)
            <x-dropdown.divider />
        @endif

        @stack('button_end_start')

        @if (! $hideButtonEnd)
            <x-dropdown.link href="{{ route($routeButtonEnd, $transaction->id) }}">
                {{ trans('recurring.end') }}
            </x-dropdown.link>
        @endif

        @stack('button_end_end')

        @if (! $hideDivider4)
            <x-dropdown.divider />
        @endif

        @stack('delete_button_start')

        @if (! $transaction->hasTransferRelation)
            @if (! $hideButtonDelete)
                @can($permissionDelete)
                    @if ($checkButtonReconciled)
                        @if (! $transaction->reconciled)
                            <x-delete-link :model="$transaction" :route="$routeButtonDelete" :text="$textDeleteModal" model-name="transaction_number" />
                        @endif
                    @else
                        <x-delete-link :model="$transaction" :route="$routeButtonDelete" :text="$textDeleteModal" model-name="transaction_number" />
                    @endif
                @endcan
            @endif
        @endif

        @stack('delete_button_end')
    </x-dropdown>
@endif

@stack('button_group_end')
