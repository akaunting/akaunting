@extends('layouts.admin')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->document_number)

@section('new_button')
    <x-documents.show.top-buttons
        type="bill"
        :document="$bill"
        hide-button-group-divider2
        hide-button-customize
    />
@endsection

@section('content')
    <x-documents.show.content
        type="bill"
        :document="$bill"
        hide-button-sent
        hide-button-email
        hide-button-share
    />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-documents.script type="bill" />
@endpush
