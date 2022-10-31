@stack('new_button_start')

@if (! $hideNewDropdown)
    <x-dropdown id="show-new-actions-{{ $contact->type }}">
        <x-slot name="trigger" class="w-full flex items-center justify-between sm:justify-start px-3 py-1.5 mb-3 sm:mb-0 bg-green hover:bg-green-700 rounded-xl text-white text-sm font-bold leading-6" override="class">
            {{ trans('general.new_more') }}
            <span class="material-icons ltr:ml-2 rtl:mr-2">expand_more</span>
        </x-slot>

        @stack('document_button_start')

        @if (! $hideButtonDocument)
            @can($permissionCreateDocument)
                <x-dropdown.link href="{{ route($routeButtonDocument, $contact->id) }}" id="show-more-actions-new-document-{{ $contact->type }}">
                    {{ trans_choice($textDocument, 1) }}
                </x-dropdown.link>
            @endcan
        @endif

        @stack('transaction_button_start')

        @if (! $hideButtonTransaction)
            @can($permissionCreateTransaction)
                <x-dropdown.link href="{{ route($routeButtonTransaction, $contact->id) }}" id="show-more-actions-new-transaction-{{ $contact->type }}">
                    {{ trans_choice($textTransaction, 1) }}
                </x-dropdown.link>
            @endcan
        @endif

        @stack('transaction_button_end')
    </x-dropdown>
@endif

@stack('edit_button_start')

@if (! $hideButtonEdit)
    @can($permissionUpdate)
        <x-link href="{{ route($routeButtonEdit, $contact->id) }}" id="show-more-actions-edit-{{ $contact->type }}">
            {{ trans('general.edit') }}
        </x-link>
    @endcan
@endif

@stack('edit_button_end')
