<div class="grid sm:grid-cols-7 sm:col-span-6 gap-x-8 gap-y-6 my-3.5">
    <div class="sm:col-span-2">
        <x-form.label for="contact" required>
            {{ trans_choice($textContact, 1) }}
        </x-form.label>

        <x-documents.form.contact
            type="{{ $typeContact }}"
            :contact="$contact"
            :contacts="$contacts"
            :search-route="$searchContactRoute"
            :create-route="$createContactRoute"
            error="form.errors.get('contact_name')"
            :text-add-contact="$textAddContact"
            :text-create-new-contact="$textCreateNewContact"
            :text-edit-contact="$textEditContact"
            :text-contact-info="$textContactInfo"
            :text-choose-different-contact="$textChooseDifferentContact"
        />
    </div>

    <div class="sm:col-span-1"></div>

    <div class="sm:col-span-2 grid gap-x-8 gap-y-6">
        @stack('issue_start')

        @if (! $hideIssuedAt)
        <div class="relative sm:col-span-3">
            <x-form.label>
                {{ trans($textIssuedAt) }}
            </x-form.label>

            <x-tooltip id="tooltip-issued" placement="bottom" message="{{ trans('documents.recurring.tooltip.document_date', ['type' => config('type.document.' . $type . '.setting.prefix', 'invoice')]) }}">
                <div class="relative focused has-label">
                    <x-form.input.text name="disabled_document_date" value="{{ trans('documents.recurring.auto_generated') }}" disabled />
                </div>
            </x-tooltip>

            <x-form.input.hidden name="issued_at" value="{{ $issuedAt }}" />
        </div>
        @endif

        @stack('due_start')

        @if (! $hideDueAt)
            <x-form.group.select
                name="payment_terms"
                label="{{ trans('invoices.payment_due') }}"
                :options="$payment_terms"
                :selected="($document) ? (string) \Date::parse($document->due_at)->diffInDays(\Date::parse($document->issued_at)) : setting($type . '.payment_terms', 0)"
                visible-change="onChangeRecurringDate"
            />  

            <x-form.input.hidden name="due_at" :value="old('due_at', $dueAt)" v-model="form.due_at" />
        @else
            <x-form.input.hidden name="due_at" :value="old('due_at', $dueAt)" v-model="form.due_at" />
        @endif
    </div>

    <div class="sm:col-span-2 grid gap-x-8 gap-y-6">
        @stack('document_number_start')

        @if (! $hideDocumentNumber)
        <div class="relative sm:col-span-3">
            <x-form.label>
                {{ trans($textDocumentNumber) }}
            </x-form.label>

            <x-tooltip id="tooltip-number" placement="bottom" message="{{ trans('documents.recurring.tooltip.document_number', ['type' => config('type.document.' . $type . '.setting.prefix', 'invoice')]) }}">
                <div class="relative focused has-label">
                    <x-form.input.text name="disabled_document_number" value="{{ trans('documents.recurring.auto_generated') }}" disabled />
                </div>
            </x-tooltip>

            <x-form.input.hidden name="document_number" value="{{ $documentNumber }}" />
        </div>
        @endif

        @stack('order_number_start')

        @if (! $hideOrderNumber)
            <x-form.group.text name="order_number" label="{{ trans($textOrderNumber) }}" value="{{ $orderNumber }}" not-required />
        @endif
    </div>
</div>
