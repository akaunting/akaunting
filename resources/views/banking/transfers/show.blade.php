<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.transfers', 1) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.transfers', 2) }}"
        icon="sync_alt"
        :route="['transfers.show', $transfer->id]"
    ></x-slot>

    <x-slot name="buttons">
        <x-transfers.show.buttons :model="$transfer" />
    </x-slot>

    <x-slot name="moreButtons">
        <x-transfers.show.more-buttons :model="$transfer" />
    </x-slot>

    <x-slot name="content">
        <x-transfers.show.content :model="$transfer" />
    </x-slot>

    @push('content_content_end')
        <akaunting-modal
            modal-dialog-class="max-w-screen-2xl"
            :show="template.modal"
            @cancel="template.modal = false"
            :title="'{{ trans('settings.transfer.choose_template') }}'"
            :message="template.html"
            :button_cancel="'{{ trans('general.button.save') }}'"
            :button_delete="'{{ trans('general.button.cancel') }}'">
            <template #modal-body>
                @include('modals.settings.transfer_template')
            </template>

            <template #card-footer>
                <div class="flex items-center justify-end">
                    <button type="button" class="px-6 py-1.5 hover:bg-gray-200 rounded-lg ltr:mr-2 rtl:ml-2" @click="closeTemplate">
                        {{ trans('general.cancel') }}
                    </button>

                    <button :disabled="form.loading" type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="addTemplate">
                        <x-button.loading>
                            {{ trans('general.confirm') }}
                        </x-button.loading>
                    </button>
                </div>
            </template>
        </akaunting-modal>
    @endpush

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-script folder="banking" file="transfers" />
</x-layouts.admin>
