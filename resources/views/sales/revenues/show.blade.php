@extends('layouts.admin')

@section('title', trans_choice('general.revenues', 2))

@section('new_button')
    @can('create-sales-revenues')
        <a href="{{ route('revenues.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        <a href="{{ route('import.create', ['group' => 'sales', 'type' => 'revenues']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endcan
    <a href="{{ route('revenues.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    <div class="card">
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/sales/revenues.js?v=' . version('short')) }}"></script>
@endpush
