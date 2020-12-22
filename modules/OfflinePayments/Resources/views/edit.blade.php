@extends('layouts.admin')

@section('title', trans('offline-payments::general.name'))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">{{ trans('offline-payments::general.add_new') }}</h3>
                </div>

                {!! Form::open([
                    'id' => 'offline-payment',
                    'route' => 'offline-payments.settings.update',
                    '@submit.prevent' => 'onSubmit',
                    '@keydown' => 'form.errors.clear($event.target.name)',
                    'files' => true,
                    'role' => 'form',
                    'class' => 'form-loading-button',
                    'novalidate' => true,
                ]) !!}

                    <div class="card-body">
                        <div class="row">
                            {{ Form::textGroup('name', trans('general.name'), 'money-check', ['required' => 'required'], null, 'col-md-12') }}

                            {{ Form::textGroup('code', trans('offline-payments::general.form.code'), 'code', ['required' => 'required'], null, 'col-md-12') }}

                            {{ Form::radioGroup('customer', trans('offline-payments::general.form.customer'), 0, trans('general.yes'), trans('general.no'), ['required' => 'required'], 'col-md-12') }}

                            {{ Form::textGroup('order', trans('offline-payments::general.form.order'), 'sort', [], null, 'col-md-12') }}

                            {{ Form::textareaGroup('description', trans('general.description')) }}

                            {!! Form::hidden('update_code', null) !!}
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row float-right">
                            {{ Form::saveButtons('settings.index') }}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-bottom-0">
                    <h3 class="mb-0">{{ trans('offline-payments::general.payment_gateways') }}</h3>
                </div>

                <div id="delete-loading"></div>

                <div class="table-responsive">
                    <table class="table table-flush table-hover" id="tbl-items">
                        <thead class="thead-light">
                            <tr class="row table-head-line">
                                <th class="col-xs-6 col-sm-4 col-md-4 col-lg-3">{{ trans('general.name') }}</th>
                                <th class="col-sm-4  col-md-4 col-lg-4 hidden-sm">{{ trans('offline-payments::general.form.code') }}</th>
                                <th class="col-lg-2 hidden-lg">{{ trans('offline-payments::general.form.order') }}</th>
                                <th class="col-xs-6 col-sm-4 col-md-4 col-lg-3 text-center">{{ trans('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($methods)
                                @foreach($methods as $item)
                                    <tr class="row align-items-center border-top-1" id="method-{{ $item->code }}">
                                        <td class="col-xs-6 col-sm-4 col-md-4 col-lg-3">{{ $item->name }}</td>
                                        <td class="col-sm-4 col-md-4 col-lg-4 hidden-sm">{{ $item->code }}</td>
                                        <td class="col-lg-2 hidden-lg">{{ $item->order }}</td>
                                        <td class="col-xs-6 col-sm-4 col-md-4 col-lg-3 text-center">
                                            <div class="dropdown">
                                                <a class="btn btn-neutral btn-sm text-light items-align-center p-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-ellipsis-h text-muted"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    {!! Form::button(trans('general.edit'), [
                                                        'type' => 'button',
                                                        'class' => 'dropdown-item method-edit',
                                                        'title' => trans('general.edit'),
                                                        'data-code' => $item->code,
                                                        'id' => 'edit-' . $item->code,
                                                        '@click' => 'onEdit',
                                                    ]) !!}
                                                    <div class="dropdown-divider"></div>
                                                    {!! Form::button(trans('general.delete'), [
                                                        'type' => 'button',
                                                        'class' => 'dropdown-item method-delete',
                                                        'title' => trans('general.delete'),
                                                        'data-code' => $item->code,
                                                        'id' => 'delete-' . $item->code,
                                                        '@click' => 'confirmDelete("' . $item->code . '", "' . trans('general.delete') . ' ' . trans_choice('offline-payments::general.methods', 1) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . $item->name . '</strong>', 'type' => mb_strtolower(trans('offline-payments::general.name'))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('modules/OfflinePayments/Resources/assets/js/offline-payments.min.js?v=' . version('short')) }}"></script>
@endpush
