@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.companies', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($company, [
            'id' => 'company',
            'method' => 'PATCH',
            'route' => ['companies.update', $company->id],
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

                    {{ Form::emailGroup('email', trans('general.email'), 'envelope') }}

                    {{ Form::selectGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', $currencies, $company->currency ?? 'USD') }}

                    {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed(), $company->locale ?? config('app.locale', 'en-GB'), []) }}

                    {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', [], $company->tax_number) }}

                    {{ Form::textGroup('phone', trans('settings.company.phone'), 'phone', [], $company->phone) }}

                    {{ Form::textareaGroup('address', trans('general.address')) }}

                    {{ Form::fileGroup('logo', trans('companies.logo'), '', ['dropzone-class' => 'form-file'], $company->company_logo) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), $company->enabled) }}
                </div>
            </div>

            @can('update-common-companies')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('companies.index') }}
                    </div>
                </div>
            @endcan
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/companies.js?v=' . version('short')) }}"></script>
@endpush
