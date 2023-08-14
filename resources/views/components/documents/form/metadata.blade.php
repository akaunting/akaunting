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

    <div class="sm:col-span-4 grid sm:grid-cols-4 gap-x-8 gap-y-6">
        @stack('issue_start')

        @if (! $hideIssuedAt)
            <x-form.group.date
                name="issued_at"
                label="{{ trans($textIssuedAt) }}"
                icon="calendar_today"
                value="{{ $issuedAt }}"
                show-date-format="{{ company_date_format() }}"
                date-format="Y-m-d"
                autocomplete="off"
                change="setDueMinDate"
                form-group-class="sm:col-span-2"
            />
        @endif

        @stack('document_number_start')

        @if (! $hideDocumentNumber)
            <x-form.group.text
                name="document_number"
                label="{{ trans($textDocumentNumber) }}"
                value="{{ $documentNumber }}"
                form-group-class="sm:col-span-2"
            />
        @endif

        @stack('due_start')

        @if (! $hideDueAt)
            <x-form.group.date
                name="due_at"
                label="{{ trans($textDueAt) }}"
                icon="calendar_today"
                value="{{ $dueAt }}"
                show-date-format="{{ company_date_format() }}"
                date-format="Y-m-d"
                autocomplete="off"
                period="{{ $periodDueAt }}"
                min-date="form.issued_at"
                min-date-dynamic="min_due_date"
                data-value-min
                form-group-class="sm:col-span-2"
            />
        @else
            <x-form.input.hidden
                name="due_at"
                :value="old('issued_at', $issuedAt)"
                v-model="form.issued_at"
                form-group-class="sm:col-span-2"
            />
        @endif

        @stack('order_number_start')

        @if (! $hideOrderNumber)
            <x-form.group.text
                name="order_number"
                label="{{ trans($textOrderNumber) }}"
                value="{{ $orderNumber }}"
                form-group-class="sm:col-span-2"
                not-required
            />
        @endif
    </div>
</div>
