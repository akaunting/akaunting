@extends('layouts.admin')

@section('title', trans_choice('general.bills', 2))

@section('new_button')
    @permission('create-purchases-bills')
        <span><a href="{{ route('bills.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ url('common/import/purchases/bills') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('bills.export', request()->input()) }}" class="btn btn-white btn-sm header-button-bottom"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    @if ($bills->count())
        <div class="card">
            <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
                {!! Form::open([
                    'url' => 'purchases/bills',
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

                    {{ Form::bulkActionRowGroup('general.bills', $bulk_actions, 'purchases/bills') }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">@sortablelink('bill_number', trans_choice('general.numbers', 1), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">@sortablelink('contact_name', trans_choice('general.vendors', 1))</th>
                            <th class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">@sortablelink('amount', trans('general.amount'))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block">@sortablelink('billed_at', trans('bills.bill_date'))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block">@sortablelink('due_at', trans('bills.due_date'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1">@sortablelink('bill_status_code', trans_choice('general.statuses', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bills as $item)
                            @php $paid = $item->paid; @endphp
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->bill_number) }}</td>
                                <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block"><a class="col-aka text-success" href="{{ route('bills.show', $item->id) }}">{{ $item->bill_number }}</a></td>
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2">{{ $item->contact_name }}</td>
                                <td class="col-md-2 col-lg-2 col-xl-2 d-none d-md-block text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block">@date($item->billed_at)</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block">@date($item->due_at)</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1">
                                    <span class="badge badge-pill badge-{{ $item->status->label }}">{{ trans('bills.status.' . $item->status->code) }}</span>
                                </td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('bills.show', $item->id) }}">{{ trans('general.show') }}</a>
                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('bills.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            @permission('create-purchases-bills')
                                                <a class="dropdown-item" href="{{ route('bills.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                            @endpermission
                                            @permission('delete-purchases-bills')
                                                <div class="dropdown-divider"></div>
                                                @if (!$item->reconciled)
                                                    {!! Form::deleteLink($item, 'purchases/bills') !!}
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
                    @include('partials.admin.pagination', ['items' => $bills])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'bills', 'docs_path' => 'purchases/bills'])
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/bills.js?v=' . version('short')) }}"></script>
@endpush
