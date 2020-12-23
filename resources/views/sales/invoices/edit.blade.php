@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.invoices', 1)]))

@section('content')
    <x-documents.form.content type="invoice" :document="$invoice" />
@endsection

@push('scripts_start')
    <x-documents.script :items="$invoice->items()->get()" />
@endpush
