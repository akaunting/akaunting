@extends('layouts.admin')

@section('title', trans_choice('general.transfers', 1))

@section('new_button')
    <x-transfers.show.top-buttons :transfer="$transfer" />
@endsection

@section('content')
    <x-transfers.show.content :transfer="$transfer" />
@endsection

@push('scripts_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">

    <x-transfers.script />
@endpush
