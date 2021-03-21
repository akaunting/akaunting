@extends('layouts.portal')

@section('title', trans_choice('general.invoices', 2))

@section('content')
    <x-documents.index.content 
        type="invoice"
        :documents="$invoices"
        hide-bulk-action
        hide-contact-name
        hide-actions
        hide-empty-page
        form-card-header-route="portal.invoices.index"
        route-button-show="portal.invoices.show"
        class-document-number="col-xs-4 col-sm-4 col-md-3 disabled-col-aka"
        class-amount="col-xs-4 col-sm-2 col-md-2 text-right"
        class-issued-at="col-sm-3 col-md-3 d-none d-sm-block"
        class-due-at="col-md-2 d-none d-md-block"
        class-status="col-xs-4 col-sm-3 col-md-2 text-center"
        search-string-model="App\Models\Portal\Sale\Invoice"
    />
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
