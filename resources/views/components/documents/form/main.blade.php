<div class="card">
    <div class="card-body">
        <x-documents.form.metadata
            type="{{ $type }}"
            :document="$document"
            hide-contact="{{ $hideContact }}"
            contact-type="{{ $contactType }}"
            hide-issue-at="{{ $hideIssuedAt }}"
            text-issue-at="{{ $textIssuedAt }}"
            hide-document-number="{{ $hideDocumentNumber }}"
            text-document-number="{{ $textDocumentNumber }}"
            hide-due-at="{{ $hideDueAt }}"
            text-due-at="{{ $textDueAt }}"
            hide-order-number="{{ $hideOrderNumber }}"
            text-order-number="{{ $textOrderNumber }}"
        />

        <x-documents.form.items 
            type="{{ $type }}"
            :document="$document"
        />

        <x-documents.form.totals
            type="{{ $type }}"
            :document="$document"
        />

        <x-documents.form.note
            type="{{ $type }}"
            :document="$document"
        />
    </div>
</div>
