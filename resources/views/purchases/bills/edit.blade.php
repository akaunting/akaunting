@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.bills', 1)]))

@section('content')
    <x-documents.form.content type="bill" :document="$bill" hide-company hide-footer hide-edit-item-columns />
@endsection

@push('scripts_start')
    <x-documents.script :items="$bill->items()->get()" />
@endpush
