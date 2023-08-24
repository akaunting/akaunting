@stack('button_group_start')

@if (! $hideMoreActions)
    <x-dropdown id="show-more-actions-{{ $document->type }}">
        <x-slot name="trigger">
            <span class="material-icons pointer-events-none">more_horiz</span>
        </x-slot>

        @stack('button_dropdown_start')

        @stack('duplicate_button_start')

        @if (! $hideDuplicate)
            @can($permissionCreate)
                <x-dropdown.link href="{{ route($duplicateRoute, $document->id) }}" id="show-more-actions-duplicate-{{ $document->type }}">
                    {{ trans('general.duplicate') }}
                </x-dropdown.link>
            @endcan
        @endif

        @stack('duplicate_button_end')

        @if (! $hideDivider1)
            <x-dropdown.divider />
        @endif

        @stack('button_print_start')

        @if (! $hidePrint)
            <x-dropdown.link href="{{ route($printRoute, $document->id) }}" target="_blank" id="show-more-actions-print-{{ $document->type }}">
                {{ trans('general.print') }}
            </x-dropdown.link>
        @endif

        @stack('button_print_end')

        @stack('button_pdf_start')

        @if (! $hidePdf)
            <x-dropdown.link href="{{ route($pdfRoute, $document->id) }}" id="show-more-actions-pdf-{{ $document->type }}">
                {{ trans('general.download_pdf') }}
            </x-dropdown.link>
        @endif

        @stack('button_pdf_end')

        @if (! in_array($document->status, $hideButtonStatuses))
            @if (! $hideDivider2)
                <x-dropdown.divider />
            @endif

            @stack('share_button_start')

            @if (! $hideShare)
                @if ($document->status != 'cancelled')
                    <x-dropdown.button id="show-more-actions-share-link-{{ $document->type }}" @click="onShareLink('{{ route($shareRoute, $document->id) }}')">
                        {{ trans('general.share_link') }}
                    </x-dropdown.button>
                @endif
            @endif

            @stack('share_button_end')

            @stack('edit_button_start')

            @if (! $hideEmail)
                @if ($document->contact_email)
                    <x-dropdown.button id="show-more-actions-send-email-{{ $document->type }}" @click="onSendEmail('{{ route($emailRoute, $document->id) }}')">
                        {{ trans($textEmail) }}
                    </x-dropdown.button>
                @else
                    <x-tooltip message="{{ trans('invoices.messages.email_required') }}" placement="left">
                        <x-dropdown.button disabled="disabled">
                            {{ trans($textEmail) }}
                        </x-dropdown.button>
                    </x-tooltip>
                @endif
            @endif
        @endif

        @stack('share_button_end')

        @if (! $hideDivider3)
            <x-dropdown.divider />
        @endif

        @stack('button_customize_start')

        @if (! $hideCustomize)
            @can($permissionCustomize)
                <x-dropdown.link href="{{ route($customizeRoute) }}" id="show-more-actions-customize-{{ $document->type }}">
                    {{ trans('general.customize') }}
                </x-dropdown.link>
            @endcan
        @endif

        @stack('button_customize_end')

        @stack('end_button_start')

        @if (! $hideEnd && $document->recurring && $document->recurring->status != 'ended')
            <x-dropdown.link href="{{ route($endRoute, $document->id) }}" id="show-more-actions-end-{{ $document->type }}">
                {{ trans('recurring.end') }}
            </x-dropdown.link>
        @endif

        @stack('end_button_end')

        @stack('button_cancelled_start')

        @if (! $hideCancel && ! in_array($document->status, ['cancelled', 'draft']))
            @can($permissionUpdate)
                <x-dropdown.divider />

                <x-dropdown.link href="{{ route($cancelledRoute, $document->id) }}" id="show-more-actions-cancel-{{ $document->type }}">
                    {{ trans('documents.actions.cancel') }}
                </x-dropdown.link>
            @endcan
        @endif

        @stack('button_cancelled_end')

        @if (! $hideDivider4)
            <x-dropdown.divider />
        @endif

        @stack('delete_button_start')

        @if (! $hideDelete)
            @can($permissionDelete)
                @if ($checkReconciled)
                    @if (! $document->reconciled)
                        <x-delete-link :model="$document" :route="$deleteRoute" :text="$textDeleteModal" model-name="document_number" />
                    @endif
                @else
                    <x-delete-link :model="$document" :route="$deleteRoute" :text="$textDeleteModal" model-name="document_number" />
                @endif
            @endcan
        @endif

        @stack('delete_button_end')

        @stack('button_dropdown_end')
    </x-dropdown>
@endif

@stack('button_group_end')
