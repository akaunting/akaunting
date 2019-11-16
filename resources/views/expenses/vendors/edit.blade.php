@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.vendors', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($vendor, [
            'id' => 'vendor',
            'method' => 'PATCH',
            'route' => ['vendors.update', $vendor->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'user') }}

                    {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                    {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                    {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $vendor->currency_code) }}

                    {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

                    {{ Form::textGroup('website', trans('general.website'), 'globe',[]) }}

                    {{ Form::textareaGroup('address', trans('general.address')) }}

                    {{ Form::fileGroup('logo', trans_choice('general.logos', 1)) }}

                    {{ Form::textGroup('reference', trans('general.reference'), 'file', []) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled')) }}
                </div>
            </div>

            @permission('update-expenses-vendors')
                <div class="card-footer">
                    <div class="float-right">
                        {{ Form::saveButtons('expenses/vendors') }}
                    </div>
                </div>
            @endpermission

            {{ Form::hidden('type', 'vendor') }}
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/expenses/vendors.js?v=' . version('short')) }}"></script>
@endpush
