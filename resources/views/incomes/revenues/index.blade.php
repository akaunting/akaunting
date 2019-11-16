@extends('layouts.admin')

@section('title', trans_choice('general.revenues', 2))

@section('new_button')
    @permission('create-incomes-revenues')
        <span><a href="{{ route('revenues.create') }}" class="btn btn-primary btn-sm btn-success header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a></span>
        <span><a href="{{ url('common/import/incomes/revenues') }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-upload"></span> &nbsp;{{ trans('import.import') }}</a></span>
    @endpermission
    <span><a href="{{ route('revenues.export', request()->input()) }}" class="btn btn-white btn-sm header-button-top"><span class="fa fa-download"></span> &nbsp;{{ trans('general.export') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-bottom-0" v-bind:class="[bulk_action.show ? 'bg-gradient-primary' : '']">
            {!! Form::open([
                'url' => 'incomes/revenues',
                'role' => 'form',
                'method' => 'GET',
                'class' => 'mb-0'
            ]) !!}
                <div class="row" v-if="!bulk_action.show">
                    <div class="col-12 card-header-search">
                        <span class="table-text hidden-lg">{{ trans('general.search') }}:</span>
                        <akaunting-search></akaunting-search>
                    </div>
                </div>

                {{ Form::bulkActionRowGroup('general.revenues', $bulk_actions, 'incomes/revenues') }}
            {!! Form::close() !!}
        </div>

        <div class="table-responsive">
            <table class="table table-flush table-hover">
                <thead class="thead-light">
                    <tr class="row table-head-line">
                        <th class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionAllGroup() }}</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-3">@sortablelink('paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                        <th class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-1 text-right">@sortablelink('amount', trans('general.amount'))</th>
                        <th class="col-md-2 col-lg-2 col-xl-3 hidden-md">@sortablelink('contact.name', trans_choice('general.customers', 1))</th>
                        <th class="col-lg-2 col-xl-2 hidden-lg">@sortablelink('category.name', trans_choice('general.categories', 1))</th>
                        <th class="col-lg-2 col-xl-1 hidden-lg">@sortablelink('account.name', trans_choice('general.accounts', 1))</th>
                        <th class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center"><a>{{ trans('general.actions') }}</a></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($revenues as $item)
                        @php $is_transfer = ($item->category && ($item->category->id == $transfer_cat_id)); @endphp
                        <tr class="row align-items-center border-top-1">
                            <td class="col-sm-2 col-md-2 col-lg-1 col-xl-1 hidden-sm">{{ Form::bulkActionGroup($item->id, $item->contact->name) }}</td>
                            @if ($item->reconciled)
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-3">@date($item->paid_at)</td>
                            @else
                                <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-3"><a class="text-success col-aka" href="{{ route('revenues.edit', $item->id) }}">@date($item->paid_at)</a></td>
                            @endif
                            <td class="col-xs-4 col-sm-4 col-md-3 col-lg-2 col-xl-1 text-right">@money($item->amount, $item->currency_code, true)</td>
                            <td class="col-md-2 col-lg-2 col-xl-3 hidden-md">{{ !empty($item->contact->name) ? $item->contact->name : trans('general.na') }}</td>
                            <td class="col-lg-2 col-xl-2 hidden-lg">{{ $item->category ? $item->category->name : trans('general.na') }}</td>
                            <td class="col-lg-2 col-xl-1 hidden-lg">{{ $item->account ? $item->account->name : trans('general.na') }}</td>
                            <td class="col-xs-4 col-sm-2 col-md-2 col-lg-1 col-xl-1 text-center">
                                @if (!$is_transfer)
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @if (!$item->reconciled)
                                                <a class="dropdown-item" href="{{ route('revenues.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                                <div class="dropdown-divider"></div>
                                            @endif
                                            @permission('create-incomes-revenues')
                                                <a class="dropdown-item" href="{{ route('revenues.duplicate', $item->id) }}">{{ trans('general.duplicate') }}</a>
                                            @endpermission

                                            @permission('delete-incomes-revenues')
                                                @if (!$item->reconciled)
                                                    <div class="dropdown-divider"></div>
                                                    {!! Form::deleteLink($item, 'incomes/revenues') !!}
                                                @endif
                                            @endpermission
                                        </div>
                                    </div>
                                @else
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="tooltip" aria-haspopup="true" aria-expanded="false" title="This Transfer, If you want to action redirect">
                                            <i class="fa fa-exchange-alt text-muted"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer table-action">
            <div class="row">
                @include('partials.admin.pagination', ['items' => $revenues, 'type' => 'revenues'])
            </div>
        </div>
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/incomes/revenues.js?v=' . version('short')) }}"></script>
@endpush
