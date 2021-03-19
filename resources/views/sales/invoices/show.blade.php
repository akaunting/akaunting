@extends('layouts.admin')

@section('title', setting('invoice.title', trans_choice('general.invoices', 1)) . ': ' . $invoice->document_number)

@section('new_button')
    <x-documents.show.top-buttons type="invoice" :document="$invoice" />
@endsection

@section('content')
    <x-documents.show.content type="invoice" :document="$invoice" hide-button-received />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-documents.script type="invoice" />
@endpush
