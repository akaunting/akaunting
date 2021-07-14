@extends('layouts.admin')

@section('title', trans_choice('general.transfers', 1))

@section('new_button')
    <x-transfers.show.top-buttons :transfer="$transfer" />
@endsection

@section('content')
    <x-transfers.show.content :transfer="$transfer" />
@endsection

@push('content_content_end')
    <akaunting-modal
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
            <div class="float-right">
                <button type="button" class="btn btn-outline-secondary" @click="closeTemplate">
                    {{ trans('general.cancel') }}
                </button>

                <button :disabled="form.loading"  type="button" class="btn btn-success button-submit" @click="addTemplate">
                    <span v-if="form.loading" class="btn-inner--icon"><i class="aka-loader"></i></span>
                    <span :class="[{'ml-0': form.loading}]" class="btn-inner--text">{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@endpush

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-transfers.script />
@endpush
