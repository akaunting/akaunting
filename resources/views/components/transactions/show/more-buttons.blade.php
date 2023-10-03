@stack('button_group_start')

@if (! $hideButtonMoreActions)
    <x-dropdown id="show-more-actions-{{ $transaction->type }}">
        <x-slot name="trigger">
            <span class="material-icons pointer-events-none">more_horiz</span>
        </x-slot>

        @stack('duplicate_button_start')

        @if (empty($transaction->document_id) && $transaction->isNotTransferTransaction())
            @if (! $hideButtonDuplicate)
                @can($permissionCreate)
                    <x-dropdown.link href="{{ route($routeButtonDuplicate, [$transaction->id, 'type' => $type]) }}" id="show-more-actions-duplicate-{{ $transaction->type }}">
                        {{ trans('general.duplicate') }}
                    </x-dropdown.link>
                @endcan
            @endif
        @endif

        @stack('duplicate_button_end')

        @stack('connect_button_start')

        @if ($transaction->is_splittable
            && $transaction->isNotSplitTransaction()
            && empty($transaction->document_id)
            && empty($transaction->recurring)
            && $transaction->isNotTransferTransaction()
        )
            @if (! $hideButtonConnect)
                @can($permissionCreate)
                <div class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap">
                    <button
                        type="button"
                        id="show-more-actions-connect-{{ $transaction->type }}"
                        class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100"
                        title="{{ trans('general.connect') }}"
                        @click="onConnectTransactions('{{ route('transactions.dial', $transaction->id) }}')">
                            {{ trans('general.connect') }}
                    </button>
                </div>
                @endcan
            @endif
        @endif

        @stack('connect_button_end')

        @if (! $hideDivider1 && $transaction->isNotDocumentTransaction() && $transaction->isNotTransferTransaction())
            <x-dropdown.divider />
        @endif

        @stack('button_print_start')

        @if (! $hideButtonPrint)
            <x-dropdown.link href="{{ route($routeButtonPrint, $transaction->id) }}" target="_blank" id="show-more-actions-print-{{ $transaction->type }}">
                {{ trans('general.print') }}
            </x-dropdown.link>
        @endif

        @stack('button_print_end')

        @stack('button_pdf_start')

        @if (! $hideButtonPdf)
            <x-dropdown.link href="{{ route($routeButtonPdf, $transaction->id) }}" class="" id="show-more-actions-pdf-{{ $transaction->type }}">
                {{ trans('general.download_pdf') }}
            </x-dropdown.link>
        @endif

        @stack('button_pdf_end')

        @if (! $hideDivider2 && $transaction->isNotTransferTransaction())
            <x-dropdown.divider />
        @endif

        @stack('share_button_start')

        @if ($transaction->isNotTransferTransaction())
            @if (! $hideButtonShare)
                <x-dropdown.button id="show-more-actions-share-link-{{ $transaction->type }}" @click="onShareLink('{{ route($shareRoute, $transaction->id) }}')">
                    {{ trans('general.share_link') }}
                </x-dropdown.button>
            @endif
        @endif

        @stack('share_button_end')

        @stack('email_button_start')

        @if ($transaction->isNotTransferTransaction())
            @if (! $hideButtonEmail)
                @if (! empty($transaction->contact) && $transaction->contact->email)
                    <x-dropdown.button id="show-more-actions-send-email-{{ $transaction->type }}" @click="onSendEmail('{{ route($routeButtonEmail, $transaction->id) }}')">
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

        @if (! $hideDivider3 && $transaction->isNotTransferTransaction())
            <x-dropdown.divider />
        @endif

        @stack('button_end_start')

        @if (! $hideButtonEnd && $transaction->recurring && $transaction->recurring->status != 'ended')
            <x-dropdown.link href="{{ route($routeButtonEnd, $transaction->id) }}" id="show-more-actions-end-{{ $transaction->type }}">
                {{ trans('recurring.end') }}
            </x-dropdown.link>
        @endif

        @stack('button_end_end')

        @if (! $hideDivider4 && $transaction->isNotTransferTransaction())
            <x-dropdown.divider />
        @endif

        @stack('delete_button_start')

        @if ($transaction->isNotTransferTransaction())
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
