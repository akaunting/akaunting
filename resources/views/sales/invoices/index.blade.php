@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 2))

@section('new_button')
    <x-documents.index.top-buttons type="invoice" />
@endsection

@section('content')
    <x-documents.index.content type="invoice" :documents="$invoices" />
@endsection

@push('scripts_start')
    <x-documents.script type="invoice" />
@endpush
