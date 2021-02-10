@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.bills', 1)]))

@section('content')
    <x-documents.form.content type="bill" :document="$bill" hide-company hide-footer hide-edit-item-columns is-purchase-price />
@endsection

@push('scripts_start')
    <x-documents.script type="bill" :items="$bill->items()->get()" />
@endpush
