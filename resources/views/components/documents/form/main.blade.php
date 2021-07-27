<div class="card">
    <div class="document-loading" v-if="!page_loaded">
        <div><i class="fas fa-spinner fa-pulse fa-7x"></i></div>
    </div>

    <div class="card-body">
        <x-documents.form.metadata
            type="{{ $type }}"
            :document="$document"
            hide-contact="{{ $hideContact }}"
            contact-type="{{ $contactType }}"
            :contact="$contact"
            :contacts="$contacts"
            :search_route="$contactSearchRoute"
            :create_route="$contactCreateRoute"
            hide-issued-at="{{ $hideIssuedAt }}"
            text-issued-at="{{ $textIssuedAt }}"
            issued-at="{{ $issuedAt }}"
            hide-document-number="{{ $hideDocumentNumber }}"
            text-document-number="{{ $textDocumentNumber }}"
            document-number="{{ $documentNumber }}"
            hide-due-at="{{ $hideDueAt }}"
            text-due-at="{{ $textDueAt }}"
            due-at="{{ $dueAt }}"
            hide-order-number="{{ $hideOrderNumber }}"
            text-order-number="{{ $textOrderNumber }}"
            order-number="{{ $orderNumber }}"
        />

        <x-documents.form.items
            type="{{ $type }}"
            :document="$document"
            hide-edit-item-columns="{{ $hideEditItemColumns }}"
            hide-items="{{ $hideItems }}"
            hide-name="{{ $hideName }}"
            hide-description="{{ $hideDescription }}"
            text-items="{{ $textItems }}"
            hide-quantity="{{ $hideQuantity }}"
            text-quantity="{{ $textQuantity }}"
            hide-price="{{ $hidePrice }}"
            text-price="{{ $textPrice }}"
            hide-discount="{{ $hideDiscount }}"
            hide-amount="{{ $hideAmount }}"
            text-amount="{{ $textAmount }}"
            is-sale-price="{{ $isSalePrice }}"
            is-purchase-price="{{ $isPurchasePrice }}"
            search-char-limit="{{ $searchCharLimit }}"
        />

        <x-documents.form.totals
            type="{{ $type }}"
            :document="$document"
        />

        <x-documents.form.note
            type="{{ $type }}"
            :document="$document"
            notes-setting="{{ $notesSetting }}"
        />
    </div>
</div>
