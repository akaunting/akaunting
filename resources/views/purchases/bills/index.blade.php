@extends('layouts.admin')

@section('title', trans_choice('general.bills', 2))

@section('new_button')
    <x-documents.index.top-buttons type="bill" />
@endsection

@section('content')
    <x-documents.index.content type="bill" :documents="$bills" />
@endsection

@push('scripts_start')
    <x-documents.script type="bill" />
@endpush
