@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 2))

@section('new_button')
    @permission('create-sales-invoices')
        <span><a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm btn-success header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ route('import.create', ['group' => 'sales', 'type' => 'invoices']) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('invoices.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    @if ($invoices->count())
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'invoices.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <akaunting-search
                            :placeholder="'{{ trans('general.search_placeholder') }}'"
                            :options="{{ json_encode([]) }}"
                        ></akaunting-search>
                    </div>

                    {{ Form::bulkActionRowGroup('general.invoices', $bulk_actions, ['group' => 'sales', 'type' => 'invoices']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block">@sortablelink('invoice_number', trans_choice('general.numbers', 1), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-4 col-sm-4 col-md-4 col-lg-2 col-xl-3 text-left">@sortablelink('contact_name', trans_choice('general.customers', 1))</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1 text-right">@sortablelink('amount', trans('general.amount'))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('invoiced_at', trans('invoices.invoice_date'))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('due_at', trans('invoices.due_date'))</th>
                            <th class="col-lg-1 col-xl-1 d-none d-lg-block text-center">@sortablelink('status', trans_choice('general.statuses', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($invoices as $item)
                            @php $paid = $item->paid; @endphp
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->invoice_number) }}</td>
                                <td class="col-md-2 col-lg-2 col-xl-1 d-none d-md-block"><a class="col-aka" href="{{ route('invoices.show' , $item->id) }}">{{ $item->invoice_number }}</a></td>
                                <td class="col-xs-4 col-sm-4 col-md-4 col-lg-2 col-xl-3 text-left">{{ $item->contact_name }}</td>
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1 text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@date($item->invoiced_at)</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@date($item->due_at)</td>
                                <td class="col-lg-1 col-xl-1 d-none d-lg-block text-center">
                                    <span class="badge badge-pill badge-{{ $item->status_label }}">{{ trans('invoices.statuses.' . $item->status) }}</span>
                                </td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('invoices.show', $item->id) }}">{{ trans('general.show') }}</a>
                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('invoices.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @endif
                                            <div class="dropdown-divider"></div>

                                            @if ($item->status != 'cancelled')
                                                @permission('create-sales-invoices')
                                                    <a class="dropdown-item" href="{{ route('invoices.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                                    <div class="dropdown-divider"></div>
                                                @endpermission

                                                @permission('update-sales-invoices')
                                                    <a class="dropdown-item" href="{{ route('invoices.cancelled', $item->id) }}">{{ trans('general.cancel') }}</a>
                                                @endpermission
                                            @endif

                                            @permission('delete-sales-invoices')
                                                @if (!$item->reconciled)
                                                    {!! Form::deleteLink($item, 'invoices.destroy') !!}
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
                    @include('partials.admin.pagination', ['items' => $invoices])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'invoices', 'docs_path' => 'sales/invoices'])
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/sales/invoices.js?v=' . version('short')) }}"></script>
@endpush
