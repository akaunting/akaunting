@extends('layouts.print')

@section('title', trans_choice('general.payments', 1) . ': ' . $payment->id)

@section('content')
    <x-transactions.template.ddefault
        type="expense"
        :transaction="$payment"
    />
@endsection
