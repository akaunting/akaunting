@extends('layouts.admin')

@section('title', trans('payments.payment_made'))

@section('new_button')
    <x-transactions.show.top-buttons type="expense" :transaction="$payment" hide-button-share hide-button-email />
@endsection

@section('content')
    <x-transactions.show.content type="expense" :transaction="$payment" />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-transactions.script type="expense" />
@endpush
