@extends('layouts.print')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->document_number)

@section('content')
    <x-documents.template.default
        type="bill"
        :document="$bill"
        hide-discount
        hide-footer
    />
@endsection
