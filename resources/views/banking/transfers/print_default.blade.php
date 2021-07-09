@extends('layouts.print')

@section('title', trans_choice('general.transfers', 1))

@section('content')
    <x-transfers.template.ddefault
        :transfer="$transfer"
    />
@endsection
