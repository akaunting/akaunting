@extends('layouts.admin')

@section('title', trans_choice('general.payments', 2))

@section('new_button')
    @can('create-purchases-payments')
        <a href="{{ route('payments.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        <a href="{{ route('import.create', ['group' => 'purchases', 'type' => 'payments']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endcan
    <a href="{{ route('payments.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    <div class="card">

    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/payments.js?v=' . version('short')) }}"></script>
@endpush
