@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'taxes.store',
            'id' => 'tax',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::textGroup('rate', trans('taxes.rate'), 'percent', ['@input' => 'onChangeTaxRate']) }}

                    {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, 'normal', ['disabledOptions' => $disable_options]) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('taxes.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/taxes.js?v=' . version('short')) }}"></script>
@endpush
