@if (empty($document))
    {!! Form::open([
        'route' => $formRoute,
        'id' => $formId,
        '@submit.prevent' => $formSubmit,
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
@else
    {!! Form::model($document, [
        'route' => [$formRoute, $document->id],
        'id' => $formId,
        'method' => 'PATCH',
        '@submit.prevent' => $formSubmit,
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true
    ]) !!}
@endif
        @if (!$hideCompany) 
            <x-documents.form.company
                type="{{ $type }}" 
                hide-logo="{{ $hideLogo }}"
                hide-document-title="{{ $hideDocumentTitle }}"
                hide-document-subheading="{{ $hideDocumentSubheading }}"
                hide-company-edit="{{ $hideCompanyEdit }}"
            />
        @endif

        <x-documents.form.main 
            type="{{ $type }}"
            :document="$document"
            hide-contact="{{ $hideContact }}"
            contact-type="{{ $contactType }}"
            :contact="$contact"
            :contacts="$contacts"
            :search_route="$contactSearchRoute"
            :create_route="$contactCreateRoute"
            hide-issue-at="{{ $hideIssuedAt }}"
            text-issue-at="{{ $textIssuedAt }}"
            issue-at="{{ $issuedAt }}"
            hide-document-number="{{ $hideDocumentNumber }}"
            text-document-number="{{ $textDocumentNumber }}"
            document-number="{{ $documentNumber }}"
            hide-due-at="{{ $hideDueAt }}"
            text-due-at="{{ $textDueAt }}"
            due-at="{{ $dueAt }}"
            hide-order-number="{{ $hideOrderNumber }}"
            text-order-number="{{ $textOrderNumber }}"
            order-number="{{ $orderNumber }}"
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
        />

        @if (!$hideFooter) 
            <x-documents.form.footer 
                type="{{ $type }}"
                :document="$document"
            />
        @endif

        @if (!$hideAdvanced) 
            <x-documents.form.advanced 
                type="{{ $type }}"
                :document="$document"
                hide-recurring="{{ $hideRecurring }}"
                hide-category="{{ $hideCategory }}"
                hide-attachment="{{ $hideAttachment }}"
            />
        @endif

        @if (!$hideButtons) 
            <x-documents.form.buttons
                type="{{ $type }}"
                :document="$document"
            />
        @endif

        {{ Form::hidden('type', old('type', $type), ['id' => 'type', 'v-model' => 'form.type']) }}
        {{ Form::hidden('status', old('status', 'draft'), ['id' => 'status', 'v-model' => 'form.status']) }}
        {{ Form::hidden('amount', old('amount', '0'), ['id' => 'amount', 'v-model' => 'form.amount']) }}
    {!! Form::close() !!}
