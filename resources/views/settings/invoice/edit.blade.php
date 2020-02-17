@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 1))

@section('content')
    {!! Form::model($setting, [
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::textGroup('number_prefix', trans('settings.invoice.prefix'), 'font', []) }}

                {{ Form::textGroup('number_digit', trans('settings.invoice.digit'), 'text-width', []) }}

                {{ Form::textGroup('number_next', trans('settings.invoice.next'), 'chevron-right', []) }}

                {{ Form::selectGroup('payment_terms', trans('settings.invoice.payment_terms'), 'calendar', $payment_terms, $setting['payment_terms'], []) }}

                {{ Form::textGroup('title', trans('settings.invoice.title'), 'font', []) }}

                {{ Form::textGroup('subheading', trans('settings.invoice.subheading'), 'font', []) }}

                {{ Form::textareaGroup('notes', trans_choice('general.notes', 2)) }}

                {{ Form::textareaGroup('footer', trans('general.footer')) }}

                {{ Form::invoice_text('item_name', trans('settings.invoice.item_name'), 'font', $item_names, null, [], 'item_name_input', null) }}

                {{ Form::invoice_text('price_name', trans('settings.invoice.price_name'), 'font', $price_names, null, [], 'price_name_input', null) }}

                {{ Form::invoice_text('quantity_name', trans('settings.invoice.quantity_name'), 'font', $quantity_names, null, [], 'quantity_name_input', null) }}

                <div class="form-group col-md-6">
                    {!! Form::label('invoice_template', trans_choice('general.templates', 1), ['class' => 'form-control-label']) !!}

                    <div class="input-group">
                        <a href="#" class="btn btn-block btn-outline-primary" @click="onTemplate">
                            <i class="fas fa-palette"></i>&nbsp; {{ trans('settings.invoice.choose_template') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @permission('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>

    {!! Form::hidden('_prefix', 'invoice') !!}

    {!! Form::close() !!}
@endsection

@push('content_content_end')
    <akaunting-modal
        :show="template.modal"
        @cancel="template.modal = false"
        :title="'{{ trans('settings.invoice.choose_template') }}'"
        :message="template.html"
        :button_cancel="'{{ trans('general.button.save') }}'"
        :button_delete="'{{ trans('general.button.cancel') }}'">
        <template #modal-body>
            @include('modals.settings.invoice_template')
        </template>

        <template #card-footer>
            <div class="float-right">
                <button type="button" class="btn btn-outline-secondary" @click="closeTemplate">
                    {{ trans('general.cancel') }}
                </button>

                <button :disabled="form.loading"  type="button" class="btn btn-success button-submit" @click="addTemplate">
                    <div class="aka-loader"></div><span>{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@endpush

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
