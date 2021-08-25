@extends('layouts.admin')

@section('title', trans_choice('general.revenues', 2))

@section('new_button')
    @can('create-sales-revenues')
        <a href="{{ route('revenues.create') }}" class="btn btn-success btn-sm">{{ trans('general.add_new') }}</a>
        <a href="{{ route('import.create', ['group' => 'sales', 'type' => 'revenues']) }}" class="btn btn-white btn-sm">{{ trans('import.import') }}</a>
    @endcan
    <a href="{{ route('revenues.export', request()->input()) }}" class="btn btn-white btn-sm">{{ trans('general.export') }}</a>
@endsection

@section('content')
    @if ($revenues->count() || request()->get('search', false))
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'revenues.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <x-search-string model="App\Models\Sale\Revenue" />
                    </div>

                    {{ Form::bulkActionRowGroup('general.revenues', $bulk_actions, ['group' => 'sales', 'type' => 'revenues']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1">@sortablelink('paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@sortablelink('amount', trans('general.amount'))</th>
                            <th class="col-md-2 col-lg-3 col-xl-3 d-none d-md-block text-left">@sortablelink('contact.name', trans_choice('general.customers', 1))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                            <th class="col-lg-2 col-xl-2 d-none d-lg-block text-left">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($revenues as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->contact->name) }}</td>
                                @if ($item->reconciled)
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1"><a class="col-aka" href="#">@date($item->paid_at)</a></td>
                                @else
                                    <td class="col-xs-4 col-sm-4 col-md-3 col-lg-1 col-xl-1"><a class="col-aka" href="{{ route('revenues.show', $item->id) }}">@date($item->paid_at)</a></td>
                                @endif
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-md-2 col-lg-3 col-xl-3 d-none d-md-block text-left">
                                    {{ $item->contact->name }}

                                    @if ($item->invoice)
                                        @if ($item->invoice->status == 'paid')
                                            <el-tooltip content="{{ $item->invoice->document_number }} / {{ trans('documents.statuses.paid') }}"
                                            effect="success"
                                            :open-delay="100"
                                            placement="top">
                                                <span class="badge badge-dot pl-2 h-0">
                                                    <i class="bg-success"></i>
                                                </span>
                                            </el-tooltip>
                                        @elseif ($item->invoice->status == 'partial')
                                            <el-tooltip content="{{ $item->invoice->document_number }} / {{ trans('documents.statuses.partial') }}"
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
                                            <a class="dropdown-item" href="{{ route('revenues.show', $item->id) }}">{{ trans('general.show') }}</a>

                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('revenues.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endif

                                            @if (empty($item->document_id))
                                            @can('create-sales-revenues')
                                                <a class="dropdown-item" href="{{ route('revenues.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endcan
                                            @endif

                                            @if (!$item->reconciled)
                                            @can('delete-sales-revenues')
                                                {!! Form::deleteLink($item, 'revenues.destroy') !!}
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
                    @include('partials.admin.pagination', ['items' => $revenues])
                </div>
            </div>
        </div>
    @else
        <x-empty-page group="sales" page="revenues" />
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transactions.js?v=' . version('short')) }}"></script>
@endpush
