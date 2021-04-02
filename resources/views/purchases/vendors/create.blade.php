@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.vendors', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'vendors.store',
            'id' => 'vendor',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button needs-validation',
            'novalidate' => 'true'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'user') }}

                    {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                    {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                    {{ Form::selectAddNewGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), ['required' => 'required', 'path' => route('modals.currencies.create'), 'field' => ['key' => 'code', 'value' => 'name']]) }}

                    {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                    {{ Form::textGroup('website', trans('general.website'), 'globe', []) }}

                    {{ Form::textareaGroup('address', trans('general.address')) }}

                    {{ Form::fileGroup('logo', trans_choice('general.pictures', 1), '', ['dropzone-class' => 'form-file']) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('vendors.index') }}
                </div>
            </div>

            {{ Form::hidden('type', 'vendor') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/vendors.js?v=' . version('short')) }}"></script>
@endpush
