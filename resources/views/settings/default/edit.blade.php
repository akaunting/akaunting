@extends('layouts.admin')

@section('title', trans_choice('general.defaults', 1))

@section('content')
    {!! Form::open([
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
                {{ Form::selectGroup('account', trans_choice('general.accounts', 1), 'university', $accounts, setting('default.account'), []) }}

                {{ Form::selectGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, setting('default.currency'), []) }}

                {{ Form::selectRemoteGroup('income_category', trans('settings.default.income_category'), 'folder', $sales_categories, setting('default.income_category'), ['remote_action' => route('categories.index'). '?search=type:income']) }}

                {{ Form::selectRemoteGroup('expense_category', trans('settings.default.expense_category'), 'folder', $purchases_categories, setting('default.expense_category'), ['remote_action' => route('categories.index'). '?search=type:expense']) }}

                {{ Form::selectGroup('tax', trans_choice('general.taxes', 1), 'percent', $taxes, setting('default.tax'), []) }}

                {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods, setting('default.payment_method'), []) }}

                {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), setting('default.locale'), []) }}

                {{ Form::selectGroup('list_limit', trans('settings.default.list_limit'), 'columns', ['10' => '10', '25' => '25', '50' => '50', '100' => '100'], setting('default.list_limit'), []) }}

                {{ Form::radioGroup('use_gravatar', trans('settings.default.use_gravatar'), setting('default.use_gravatar')) }}
            </div>
        </div>

        @can('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endcan
    </div>

    {!! Form::hidden('_prefix', 'default') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
