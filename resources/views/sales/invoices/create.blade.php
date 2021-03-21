@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => setting('invoice.title', trans_choice('general.invoices', 1))]))

@section('content')
    <x-documents.form.content type="invoice" />
@endsection

@push('scripts_start')
    <x-documents.script type="invoice" />
@endpush
