@extends('layouts.admin')

@section('title', trans_choice('general.payments', 2))

@section('new_button')
    @permission('create-purchases-payments')
        <span><a href="{{ route('payments.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ url('common/import/purchases/payments') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('payments.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    @if ($payments->count())
        <div class="card">
            <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
                {!! Form::open([
                    'url' => 'purchases/payments',
                    'role' => 'form',
                    'method' => 'GET',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="row" v-if="!bulk_action.show">
                        <div class="col-12 d-flex align-items-center">
                            <span class="font-weight-400 d-none d-lg-block mr-2">{{ trans('general.search') }}:</span>
                            <akaunting-search></akaunting-search>
                        </div>
                    </div>

                    {{ Form::bulkActionRowGroup('general.payments', $bulk_actions, 'purchases/payments') }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">@sortablelink('paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                            <th class="col-md-2 col-lg-2 col-xl-3 d-none d-md-block text-left">@sortablelink('contact.name', trans_choice('general.vendors', 1))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                            <th class="col-lg-2 col-xl-1 d-none d-lg-block text-left">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($payments as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->contact->name) }}</td>
                                @if ($item->reconciled)
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">@date($item->paid_at)</td>
                                @else
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2"><a class="col-aka" href="{{ route('payments.edit', $item->id) }}">@date($item->paid_at)</a></td>
                                @endif
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-md-2 col-lg-2 col-xl-3 d-none d-md-block text-left">{{ $item->contact->name }}</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left">{{ $item->category->name }}</td>
                                <td class="col-lg-2 col-xl-1 d-none d-lg-block text-left">{{ $item->account->name }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('payments.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endif
                                            @permission('create-purchases-payments')
                                                <a class="dropdown-item" href="{{ route('payments.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                            @endpermission
                                            @permission('delete-purchases-payments')
                                                @if (!$item->reconciled)
                                                    <div class="dropdown-divider"></div>
                                                    {!! Form::deleteLink($item, 'purchases/payments') !!}
                                                @endif
                                            @endpermission
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer table-action">
                <div class="row">
                    @include('partials.admin.pagination', ['items' => $payments])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'payments', 'docs_path' => 'purchases/payments'])
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/payments.js?v=' . version('short')) }}"></script>
@endpush
