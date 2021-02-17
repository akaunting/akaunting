@if ($hideEmptyPage || ($documents->count() || request()->get('search', false)))
    <div class="card">
        <x-documents.index.card-header
            type="{{ $type }}"
            hide-bulk-action="{{ $hideBulkAction }}"
            :form-card-header-route="$formCardHeaderRoute"
            hide-search-string="{{ $hideSearchString }}"
            search-string-model="{{ $searchStringModel }}"
            text-bulk-action="{{ $textBulkAction }}"
            bulk-action-class="{{ $bulkActionClass }}"
            :bulk-actions="$bulkActions"
            :bulk-action-route-parameters="$bulkActionRouteParameters"
        />

        <x-documents.index.card-body
            type="{{ $type }}"
            :documents="$documents"
            hide-bulk-action="{{ $hideBulkAction }}"
            class-bulk-action="{{ $classBulkAction }}"
            hide-document-number="{{ $hideDocumentNumber }}"
            text-document-number="{{ $textDocumentNumber }}"
            class-document-number="{{ $classDocumentNumber }}"
            hide-contact-name="{{ $hideContactName }}"
            text-contact-name="{{ $textContactName }}"
            class-contact-name="{{ $classContactName }}"
            hide-amount="{{ $hideAmount }}"
            class-amount="{{ $classAmount }}"
            hide-issued-at="{{ $hideIssuedAt }}"
            text-issued-at="{{ $textIssuedAt }}"
            class-issued-at="{{ $classIssuedAt }}"
            hide-due-at="{{ $hideDueAt }}"
            class-due-at="{{ $classDueAt }}"
            text-due-at="{{ $textDueAt }}"
            hide-status="{{ $hideStatus }}"
            class-status="{{ $classStatus }}"
            hide-actions="{{ $hideActions }}"
            class-actions="{{ $classActions }}"
            text-document-status="{{ $textDocumentStatus }}"
            hide-button-show="{{ $hideButtonShow }}"
            route-button-show="{{ $routeButtonShow }}"
            hide-button-edit="{{ $hideButtonEdit }}"
            check-button-reconciled="{{ $checkButtonReconciled }}"
            route-button-edit="{{ $routeButtonEdit }}"
            check-button-cancelled="{{ $checkButtonCancelled }}"
            hide-button-duplicate="{{ $hideButtonDuplicate }}"
            permission-create="{{ $permissionCreate }}"
            route-button-duplicate="{{ $routeButtonDuplicate }}"
            hide-button-cancel="{{ $hideButtonCancel }}"
            permission-update="{{ $permissionUpdate }}"
            route-button-cancelled="{{ $routeButtonCancelled }}"
            hide-button-delete="{{ $hideButtonDelete }}"
            permission-delete="{{ $permissionDelete }}"
            route-button-delete="{{ $routeButtonDelete }}"
            text-modal-delete="{{ $textModalDelete }}"
            value-modal-delete="{{ $valueModalDelete }}"
        />

        <x-documents.index.card-footer
            type="{{ $type }}"
            :documents="$documents"
        />
    </div>
@else
    <x-documents.index.empty-page
        type="{{ $type }}"
        image-empty-page="{{ $imageEmptyPage }}"
        text-empty-page="{{ $textEmptyPage }}"
        url-docs-path="{{ $urlDocsPath }}"
        create-route="{{ $createRoute }}"
        check-permission-create="{{ $checkPermissionCreate }}"
        permission-create="{{ $permissionCreate }}"
    />
@endif
