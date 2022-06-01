@stack('add_new_button_start')

@can('create-banking-transfers')
    <x-link href="{{ route('transfers.create') }}" kind="primary">
        {{ trans('general.title.new', ['type' => trans_choice('general.transfers', 1)]) }}
    </x-link>
@endcan

@stack('add_new_button_end')
