@extends('layouts.signed')

@section('title', trans_choice('general.payments', 1) . ': ' . @date($payment->paid_at))

@section('new_button')
    @stack('button_print_start')
    <a href="{{ $print_action }}" target="_blank" class="btn btn-white btn-sm">
        {{ trans('general.print') }}
    </a>
    @stack('button_print_end')

    @stack('button_pdf_start')
    <a href="{{ $pdf_action }}" class="btn btn-white btn-sm">
        {{ trans('general.download') }}
    </a>
    @stack('button_pdf_end')

    @stack('button_dashboard_start')
    @if (!user())
        <a href="{{ route('portal.dashboard') }}" class="btn btn-white btn-sm">
            {{ trans('payments.all_payments') }}
        </a>
    @endif
    @stack('button_dashboard_end')
@endsection

@section('content')
    <x-transactions.show.header
        type="payment"
        :transaction="$payment"
        hide-header-contact
        class-header-status="col-md-8"
    />

    <x-transactions.show.transaction
        type="payment"
        :transaction="$payment"
        transaction-template="{{ setting('payment.template', 'default') }}"
        hide-payment-methods
    />
@endsection

@push('footer_start')
    <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    <script src="{{ asset('public/js/portal/payments.js?v=' . version('short')) }}"></script>
@endpush
