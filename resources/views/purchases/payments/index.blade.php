@extends('layouts.admin')

@section('title', trans_choice('general.payments', 2))

@section('new_button')
    @can('create-purchases-payments')
        <a href="{{ route('payments.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        <a href="{{ route('import.create', ['group' => 'purchases', 'type' => 'payments']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endcan
    <a href="{{ route('payments.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    @if ($payments->count() || request()->get('search', false))
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'payments.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <x-search-string model="App\Models\Purchase\Payment" />
                    </div>

                    {{ Form::bulkActionRowGroup('general.payments', $bulk_actions, ['group' => 'purchases', 'type' => 'payments']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1">@sortablelink('paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                            <th class="col-md-2 col-lg-3 col-xl-3 d-none d-md-block text-left">@sortablelink('contact.name', trans_choice('general.vendors', 1))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($payments as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->contact->name) }}</td>
                                @if ($item->reconciled)
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1"><a class="col-aka" href="#">@date($item->paid_at)</a></td>
                                @else
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1"><a class="col-aka" href="{{ route('payments.show', $item->id) }}">@date($item->paid_at)</a></td>
                                @endif
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-md-2 col-lg-3 col-xl-3 d-none d-md-block text-left">
                                    {{ $item->contact->name }}

                                    @if($item->bill)
                                        @if ($item->bill->status == 'paid')
                                            <el-tooltip content="{{ $item->bill->document_number }} / {{ trans('documents.statuses.paid') }}"
                                            effect="success"
                                            :open-delay="100"
                                            placement="top">
                                                <span class="badge badge-dot pl-2 h-0">
                                                    <i class="bg-success"></i>
                                                </span>
                                            </el-tooltip>
                                        @elseif ($item->bill->status == 'partial')
                                            <el-tooltip content="{{ $item->bill->document_number }} / {{ trans('documents.statuses.partial') }}"
                                            effect="info"
                                            :open-delay="100"
                                            placement="top">
                                                <span class="badge badge-dot pl-2 h-0">
                                                    <i class="bg-info"></i>
                                                </span>
                                            </el-tooltip>
                                        @endif
                                    @endif
                                </td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left long-texts">{{ $item->category->name }}</td>
                                <td class="col-lg-2 col-xl-2 d-none d-lg-block text-left long-texts">{{ $item->account->name }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('payments.show', $item->id) }}">{{ trans('general.show') }}</a>

                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('payments.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endif

                                            @if (empty($item->document_id))
                                            @can('create-purchases-payments')
                                                <a class="dropdown-item" href="{{ route('payments.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endcan
                                            @endif

                                            @if (!$item->reconciled)
                                            @can('delete-purchases-payments')
                                                {!! Form::deleteLink($item, 'payments.destroy') !!}
                                            @endcan
                                            @endif
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
        <x-empty-page group="purchases" page="payments" />
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transactions.js?v=' . version('short')) }}"></script>
@endpush
