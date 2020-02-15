@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.companies', 1)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'id' => 'company',
            'route' => 'companies.store',
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

                    {{ Form::selectGroup('currency', trans_choice('general.currencies', 1), 'exchange-alt', $currencies) }}

                    {{ Form::textGroup('domain', trans('companies.domain'), 'globe', []) }}

                    {{ Form::textareaGroup('address', trans('general.address')) }}

                    {{ Form::fileGroup('logo', trans('companies.logo')) }}

                    {{ Form::radioGroup('enabled', trans('general.enabled'), true) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('companies.index') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/companies.js?v=' . version('short')) }}"></script>
@endpush
