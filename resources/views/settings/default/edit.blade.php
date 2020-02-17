@extends('layouts.admin')

@section('title', trans_choice('general.defaults', 1))

@section('content')
    {!! Form::model($setting, [
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::selectGroup('account', trans_choice('general.accounts', 1), 'university', $accounts, !empty($setting['account']) ? $setting['account'] : null, []) }}

                {{ Form::selectGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, !empty($setting['currency']) ? $setting['currency'] : null, []) }}

                {{ Form::selectGroup('tax', trans_choice('general.taxes', 1), 'percent', $taxes, !empty($setting['tax']) ? $setting['tax'] : null, []) }}

                {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, !empty($setting['payment_method']) ? $setting['payment_method'] : null, []) }}

                {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), !empty($setting['locale']) ? $setting['locale'] : null, []) }}

                {{ Form::selectGroup('list_limit', trans('settings.default.list_limit'), 'columns', ['10' => '10', '25' => '25', '50' => '50', '100' => '100'], !empty($setting['list_limit']) ? $setting['list_limit'] : null, []) }}

                {{ Form::radioGroup('use_gravatar', trans('settings.default.use_gravatar'), $setting->get('use_gravatar')) }}
            </div>
        </div>

        @permission('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>

    {!! Form::hidden('_prefix', 'default') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
