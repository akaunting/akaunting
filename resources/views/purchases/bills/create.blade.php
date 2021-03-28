@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => setting('bill.title', trans_choice('general.bills', 1))]))

@section('content')
    <x-documents.form.content type="bill" hide-company hide-footer hide-edit-item-columns is-purchase-price />
@endsection

@push('scripts_start')
    <x-documents.script type="bill" />
@endpush
