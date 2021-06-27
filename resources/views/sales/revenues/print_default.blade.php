@extends('layouts.print')

@section('title', trans_choice('general.revenues', 1) . ': ' . $revenue->id)

@section('content')
    <x-transactions.template.ddefault
        type="revenue"
        :transaction="$revenue"
    />
@endsection
