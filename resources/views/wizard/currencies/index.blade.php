@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
    <div class="card">
        @include('partials.wizard.steps')

        <div class="card-body border-bottom-0">
            <div class="row">
                <div class="col-md-12 text-right">
                    <span>
                        <button type="button" @click="onAddCurrency" class="btn btn-success header-button-top btn-sm">
                            <span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}
                        </button>
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            {!! Form::open([
                'route' => 'wizard.currencies.store',
                'id' => 'currency',
                '@submit.prevent' => 'onSubmit',
                '@keydown' => 'form.errors.clear($event.target.name)',
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button mb-0',
                'novalidate' => true
            ]) !!}
                <table class="table table-flush table-hover" id='tbl-currencies'>
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-xs-4 col-sm-4 col-md-3">@sortablelink('name', trans('general.name'))</th>
                            <th class="col-md-3 d-none d-md-block">@sortablelink('code', trans('currencies.code'))</th>
                            <th class="col-md-2 d-none d-md-block">@sortablelink('rate', trans('currencies.rate'))</th>
                            <th class="col-xs-4 col-sm-4 col-md-2 ">@sortablelink('enabled', trans('general.enabled'))</th>
                            <th class="col-xs-4 col-sm-4 col-md-2 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $item)
                            <tr class="row align-items-center border-top-1" id="currency-{{ $item->id }}">
                                <td class="col-xs-4 col-sm-4 col-md-3">
                                    <a href="javascript:void(0);" @click="onEditCurrency('{{ $item->id }}')">
                                        {{ $item->name }}
                                    </a>
                                </td>
                                <td class="col-md-3 d-none d-md-block">{{ $item->code }}</td>
                                <td class="col-md-2 d-none d-md-block">{{ $item->rate }}</td>
                                <td class="col-xs-4 col-sm-4 col-md-2">
                                    @if (user()->can('update-settings-currencies'))
                                        {{ Form::enabledGroup($item->id, $item->name, $item->enabled) }}
                                    @else
                                        @if ($item->enabled)
                                            <badge rounded type="success" class="mw-60">{{ trans('general.yes') }}</badge>
                                        @else
                                            <badge rounded type="danger" class="mw-60">{{ trans('general.no') }}</badge>
                                        @endif
                                    @endif
                                </td>
                                <td class="col-xs-4 col-sm-4 col-md-2 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <button type="button" class="dropdown-item" @click="onEditCurrency('{{ $item->id }}')">
                                                {{ trans('general.edit') }}
                                            </button>
                                            @permission('delete-settings-currencies')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'wizard/currencies') !!}
                                            @endpermission
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="row align-items-center border-top-1" v-show="show">
                            <td class="col-xs-4 col-sm-4 col-md-3">
                                {{ Form::textGroup('name', trans('general.name'), 'font', [], null, '') }}
                            </td>
                            <td class="col-md-3 d-none d-md-block">
                                {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes, null, ['required' => 'required', 'change' => 'onChangeCode'], '') }}
                            </td>
                            <td class="col-md-2 d-none d-md-block">
                                {{ Form::textGroup('rate', trans('currencies.rate'), 'percentage', ['required' => 'required'], null, '') }}
                            </td>
                            <td class="col-xs-4 col-sm-4 col-md-2">
                                {{ Form::radioGroup('enabled', trans('general.enabled')) }}
                            </td>
                            <td class="col-xs-4 col-sm-4 col-md-2 text-center">
                                {!! Form::button(
                                    '<span class="btn-inner--icon"><i class="fas fa-save"></i></span>', [
                                    ':disabled' => 'form.loading',
                                    'type' => 'submit',
                                    'class' => 'btn btn-success',
                                    'data-loading-text' => trans('general.loading'),
                                ]) !!}

                                <div class="d-none">
                                    {{ Form::numberGroup('precision', trans('currencies.precision'), 'bullseye') }}

                                    {{ Form::textGroup('symbol', trans('currencies.symbol.symbol'), 'font') }}

                                    {{ Form::selectGroup('symbol_first', trans('currencies.symbol.position'), 'text-width', ['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]) }}

                                    {{ Form::textGroup('decimal_mark', trans('currencies.decimal_mark'), 'columns') }}

                                    {{ Form::textGroup('thousands_separator', trans('currencies.thousands_separator'), 'columns', []) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" name="bulk_action_path" value="settings/currencies"/>
            {!! Form::close() !!}
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ url('wizard/companies') }}" class="btn btn-icon btn-white header-button-top">
                        <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i></span>
                        <span class="btn-inner--text">{{ trans('modules.back') }}</span>
                    </a>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url('wizard/taxes') }}" id="wizard-skip" class="btn btn-icon btn-white header-button-top">
                        <span class="btn-inner--icon"><i class="fas fa-arrow-right"></i></span>
                        <span class="btn-inner--text">{{ trans('general.skip') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var currencies = {!! json_encode($currencies->items()) !!}
    </script>

    <script src="{{ asset('public/js/wizard/currencies.js?v=' . version('short')) }}"></script>
@endpush
