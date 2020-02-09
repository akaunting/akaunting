@extends('layouts.admin')

@section('title', trans_choice('general.transfers', 2))

@permission('create-banking-transfers')
    @section('new_button')
        <a href="{{ route('transfers.create') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-plus"></span> &nbsp;{{ trans('general.add_new') }}</a>
    @endsection
@endpermission

@section('content')
    @if ($transfers->count())
        <div class="card">
            <div class="card-header border-bottom-0" :class="[{'bg-gradient-primary': bulk_action.show}]">
                {!! Form::open([
                    'method' => 'GET',
                    'route' => 'transfers.index',
                    'role' => 'form',
                    'class' => 'mb-0'
                ]) !!}
                    <div class="align-items-center" v-if="!bulk_action.show">
                        <akaunting-search
                            :placeholder="'{{ trans('general.search_placeholder') }}'"
                            :options="{{ json_encode([]) }}"
                        ></akaunting-search>
                    </div>

                    {{ Form::bulkActionRowGroup('general.transfers', $bulk_actions, ['group' => 'banking', 'type' => 'transfers']) }}
                {!! Form::close() !!}
            </div>

            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="row table-head-line">
                            <th class="col-sm-2 col-md-1 d-none d-sm-block">{{ Form::bulkActionAllGroup() }}</th>
                            <th class="col-md-2 d-none d-md-block">@sortablelink('expense_transaction.paid_at', trans('general.date'), ['filter' => 'active, visible'], ['class' => 'col-aka', 'rel' => 'nofollow'])</th>
                            <th class="col-sm-2 col-md-3 d-none d-sm-block">@sortablelink('expense_transaction.name', trans('transfers.from_account'))</th>
                            <th class="col-xs-4 col-sm-4 col-md-2">@sortablelink('income_transaction.name', trans('transfers.to_account'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 text-right">@sortablelink('expense_transaction.amount', trans('general.amount'))</th>
                            <th class="col-xs-4 col-sm-2 col-md-2 text-center">{{ trans('general.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transfers as $item)
                            <tr class="row align-items-center border-top-1">
                                <td class="col-sm-2 col-md-1 d-none d-sm-block">{{ Form::bulkActionGroup($item->id, $item->from_account) }}</td>
                                <td class="col-md-2 d-none d-md-block"><a class="col-aka" href="{{ route('transfers.edit', $item->id) }}">@date($item->paid_at)</a></td>
                                <td class="col-sm-2 col-md-3 d-none d-sm-block">{{ $item->from_account }}</td>
                                <td class="col-xs-4 col-sm-4 col-md-2">{{ $item->to_account }}</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-right">@money($item->amount, $item->currency_code, true)</td>
                                <td class="col-xs-4 col-sm-2 col-md-2 text-center">
                                    <div class="dropdown">
                                        <a class="btn btn-neutral btn-sm text-light items-align-center py-2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-muted"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            <a class="dropdown-item" href="{{ route('transfers.edit', $item->id) }}">{{ trans('general.edit') }}</a>
                                            @permission('delete-banking-transfers')
                                                <div class="dropdown-divider"></div>
                                                {!! Form::deleteLink($item, 'transfers.destroy') !!}
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
                    @include('partials.admin.pagination', ['items' => $transfers])
                </div>
            </div>
        </div>
    @else
        @include('partials.admin.empty_page', ['page' => 'transfers', 'docs_path' => 'banking/transfers'])
    @endif
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/banking/transfers.js?v=' . version('short')) }}"></script>
@endpush
