@extends('layouts.print')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->document_number)

@section('content')
    <x-documents.template.modern
        type="invoice"
        :document="$invoice"
    />
@endsection
