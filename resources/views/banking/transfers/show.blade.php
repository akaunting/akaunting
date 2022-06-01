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

                    <button :disabled="form.loading"  type="button" class="relative flex items-center justify-center bg-green hover:bg-green-700 text-white px-6 py-1.5 text-base rounded-lg disabled:bg-green-100" @click="addTemplate">
                        <i v-if="form.loading" class="animate-submit delay-[0.28s] absolute w-2 h-2 rounded-full left-0 right-0 -top-3.5 m-auto before:absolute before:w-2 before:h-2 before:rounded-full before:animate-submit before:delay-[0.14s] after:absolute after:w-2 after:h-2 after:rounded-full after:animate-submit before:-left-3.5 after:-right-3.5 after:delay-[0.42s]"></i>
                        <span :class="[{'opacity-0': form.loading}]">{{ trans('general.confirm') }}</span>
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
