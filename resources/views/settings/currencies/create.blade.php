@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'route' => 'currencies.store',
            'id' => 'currency',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'chart-bar') }}

                    {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes, null, ['required' => 'required', 'change' => 'onCodeAccount']) }}

                    {{ Form::textGroup('rate', trans('currencies.rate'), 'sliders-h', ['@input' => 'RateReplace', 'required' => 'required']) }}

                    {{ Form::numberGroup('precision', trans('currencies.precision'), 'dot-circle') }}

                    {{ Form::textGroup('symbol', trans('currencies.symbol.symbol'), 'font') }}

                    {{ Form::selectGroup('symbol_first', trans('currencies.symbol.position'), 'text-width', ['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]) }}

                    {{ Form::textGroup('decimal_mark', trans('currencies.decimal_mark'), 'sign') }}

                    {{ Form::textGroup('thousands_separator', trans('currencies.thousands_separator'), 'slash', []) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled')) }}

                    {{ Form::radioGroup('default_currency', trans('currencies.default')) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('settings/currencies') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/currencies.js?v=' . version('short')) }}"></script>
@endpush
