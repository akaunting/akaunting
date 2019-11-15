@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <div class="row mb--4">
                    <hr class="wizard-line">
                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{ url('wizard/companies') }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('general.companies', 1) }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <a href="{{ url('wizard/currencies') }}">
                                <button type="button" class="btn btn-secondary btn-lg wizard-steps wizard-steps-color-active rounded-circle">
                                    <span class="btn-inner--icon wizard-steps-inner"><i class="fa fa-check"></i></span>
                                </button>
                                <p class="mt-2 text-muted step-text">{{ trans_choice('general.currencies', 2) }}</p>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button href="#step-3" type="button" class="btn btn-default btn-lg wizard-steps rounded-circle">
                                <span class="btn-inner--icon wizard-steps-inner">3</span>
                            </button>
                            <p class="mt-2 after-step-text">{{ trans_choice('general.taxes', 2) }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="text-center">
                            <button type="button" class="btn btn-secondary btn-lg wizard-steps rounded-circle steps">
                                <span class="btn-inner--icon wizard-steps-inner wizard-steps-color">4</span>
                            </button>
                            <p class="mt-2 text-muted step-text">{{ trans_choice('general.finish', 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="row">
                <div class="col-6">
                    <h3 class="mb-0">{{ trans_choice('general.taxes', 2) }}</h3>
                </div>
                <div class="col-6 text-right">
                    <span class="new-button">
                        <button type="button" @click="onAddTax" class="btn btn-success btn-sm">
                            <span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            {!! Form::open([
                'route' => 'wizard.taxes.store',
                'id' => 'tax',
                '@submit.prevent' => 'onSubmit',
                '@keydown' => 'form.errors.clear($event.target.name)',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button mb-0',
                'novalidate' => true
            ]) !!}
                <table class="table table-flush table-hover" id='tbl-taxes'>
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-xs-4 col-sm-3">@sortablelink('name', trans('general.name'))</th>
                            <th class="col-sm-3 hidden-sm">@sortablelink('rate', trans('taxes.rate_percent'))</th>
                            <th class="col-xs-4 col-sm-3">@sortablelink('enabled', trans('general.enabled'))</th>
                            <th class="col-xs-4 col-sm-3 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxes as $item)
                            <tr class="row align-items-center border-top-1" id="tax-{{ $item->id }}">
                                <td class="col-xs-4 col-sm-3 tax-name">
                                    <a href="javascript:void(0);" class="text-success" @click="onEditTax('{{ $item->id }}')">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="col-sm-3 hidden-sm">{{ $item->rate }}</td>
                                <td class="col-xs-4 col-sm-3">
                                    @if (user()->can('update-settings-taxes'))
                                        {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                    @else
                                        @if ($item->enabled)
                                            <badge rounded type="success">{{ trans('general.enabled') }}</badge>
                                        @else
                                            <badge rounded type="danger">{{ trans('general.disabled') }}</badge>
                                        @endif
                                    @endif
                                </td>
                                <td class="col-xs-4 col-sm-3 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <button type="button" class="dropdown-item" @click="onEditTax('{{ $item->id }}')">
                                                {{ trans('general.edit') }}
                                            </button>
                                            @permission('delete-settings-taxes')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'wizard/taxes') !!}
                                            @endpermission
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="row align-items-center border-top-1" v-show="show">
                            <td class="col-xs-4 col-sm-3 tax-name">
                                {{ Form::textGroup('name', trans('general.name'), 'id-card-o', [], null, '') }}
                            </td>
                            <td class="col-sm-3 hidden-sm tax-rate">
                                {{ Form::textGroup('rate', trans('currencies.rate'), 'money', ['required' => 'required'], null, '') }}
                            </td>
                            <td class="col-xs-4 col-sm-3 tax-status">
                                {{ Form::radioGroup('enabled', trans('general.enabled')) }}
                            </td>
                            <td class="col-xs-4 col-sm-3 text-center">
                                {!! Form::button(
                                    '<div v-if="form.loading" :class="(form.loading) ? \'show \' : \'\'"  class="aka-loader-frame d-none"><div class="aka-loader"></div></div> <span v-if="!form.loading" class="btn-inner--icon"><i class="fas fa-save"></i></span>', [
                                    ':disabled' => 'form.loading',
                                    'type' => 'submit',
                                    'class' => 'btn btn-success  tax-submit',
                                    'data-loading-text' => trans('general.loading'),
                                    'style' => 'padding: 9px 14px; margin-top: 10px;'
                                ]) !!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="bulk_action_path" value="settings/taxes" />
            {!! Form::close() !!}
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ url('wizard/finish') }}" id="wizard-skip" class="btn btn-white">
                        <span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var taxes = {!! json_encode($taxes->items()) !!}
    </script>

    <script src="{{ asset('public/js/wizard/taxes.js?v=' . version('short')) }}"></script>
@endpush
