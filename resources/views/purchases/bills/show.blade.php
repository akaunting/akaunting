@extends('layouts.admin')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    @stack('recurring_message_start')
        @if (($recurring = $bill->recurring) && ($next = $recurring->getNextRecurring()))
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="media">
                        <div class="media-body">
                            <div class="media-comment-text">
                                <div class="d-flex">
                                    @stack('recurring_message_head_start')
                                        <h5 class="mt-0">{{ trans('recurring.recurring') }}</h5>
                                    @stack('recurring_message_head_end')
                                </div>

                                @stack('recurring_message_body_start')
                                    <p class="text-sm lh-160 mb-0">{{ trans('recurring.message', [
                                        'type' => mb_strtolower(trans_choice('general.bills', 1)),
                                        'date' => $next->format($date_format)
                                    ]) }}
                                    </p>
                                @stack('recurring_message_body_end')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @stack('recurring_message_end')

    @stack('status_message_start')
        @if ($bill->status == 'draft')
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="alert alert-danger fade show" role="alert">
                        @stack('status_message_body_start')
                            <span class="alert-text">
                                <strong>{!! trans('bills.messages.draft') !!}</strong>
                            </span>
                        @stack('status_message_body_end')
                    </div>
                </div>
            </div>
        @endif
    @stack('status_message_end')

    @stack('timeline_start')
        @if (!in_array($bill->status, ['paid', 'cancelled']))
            @stack('timeline_body_start')
                <div class="card">
                    <div class="card-body">
                        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                            @stack('timeline_body_create_bill_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-primary">
                                        <i class="fas fa-plus"></i>
                                    </span>

                                    <div class="timeline-content">
                                        @stack('timeline_body_create_bill_head_start')
                                            <h2 class="font-weight-500">{{ trans('bills.create_bill') }}</h2>
                                        @stack('timeline_body_create_bill_head_end')

                                        @stack('timeline_body_create_bill_body_start')
                                            @stack('timeline_body_create_bill_body_message_start')
                                                <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                                <small>{{ trans('bills.messages.status.created', ['date' => Date::parse($bill->created_at)->format($date_format)]) }}</small>
                                            @stack('timeline_body_create_bill_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_create_bill_body_button_edit_start')
                                                    <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-primary btn-sm btn-alone">
                                                        {{ trans('general.edit') }}
                                                    </a>
                                                @stack('timeline_body_create_bill_body_button_edit_end')
                                            </div>
                                        @stack('timeline_body_create_bill_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_create_bill_end')

                            @stack('timeline_body_receive_bill_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-danger">
                                        <i class="far fa-envelope"></i>
                                    </span>

                                    <div class="timeline-content">
                                        @stack('timeline_body_receive_bill_head_start')
                                            <h2 class="font-weight-500">{{ trans('bills.receive_bill') }}</h2>
                                        @stack('timeline_body_receive_bill_head_end')

                                        @stack('timeline_body_receive_bill_body_start')
                                            @if ($bill->status == 'draft')
                                                @stack('timeline_body_receive_bill_body_message_start')
                                                    <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                                    <small>{{ trans('bills.messages.status.receive.draft') }}</small>
                                                @stack('timeline_body_receive_bill_body_message_end')

                                                <div class="mt-3">
                                                    @stack('timeline_body_receive_bill_body_button_received_start')
                                                        @permission('update-purchases-bills')
                                                            <a href="{{ route('bills.received', $bill->id) }}" class="btn btn-danger btn-sm btn-alone">{{ trans('bills.mark_received') }}</a>
                                                        @endpermission
                                                    @stack('timeline_body_receive_bill_body_button_received_end')
                                                </div>
                                            @else
                                                @stack('timeline_body_receive_bill_body_message_start')
                                                    <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                                    <small>{{ trans('bills.messages.status.receive.received', ['date' => Date::parse($bill->received_at)->format($date_format)]) }}</small>
                                                @stack('timeline_body_receive_bill_body_message_end')
                                            @endif
                                        @stack('timeline_body_receive_bill_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_receive_bill_end')

                            @stack('timeline_body_make_payment_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-success">
                                        <i class="far fa-money-bill-alt"></i>
                                    </span>

                                    <div class="timeline-content">
                                        @stack('timeline_body_make_payment_head_start')
                                            <h2 class="font-weight-500">{{ trans('bills.make_payment') }}</h2>
                                        @stack('timeline_body_make_payment_head_end')

                                        @stack('timeline_body_make_payment_body_start')
                                            @stack('timeline_body_get_paid_body_message_start')
                                                @if($bill->status != 'paid' && empty($bill->transactions->count()))
                                                    <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                                    <small>{{ trans('bills.messages.status.paid.await') }}</small>
                                                @else
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('general.partially_paid') }}</small>
                                                @endif
                                            @stack('timeline_body_make_payment_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_get_paid_body_button_pay_start')
                                                    @permission('update-purchases-bills')
                                                        <a href="{{ route('bills.paid', $bill->id) }}" class="btn btn-white btn-sm header-button-top">{{ trans('bills.mark_paid') }}</a>
                                                    @endpermission
                                                @stack('timeline_body_get_paid_body_button_pay_end')

                                                @stack('timeline_body_make_payment_body_button_payment_start')
                                                    @if(empty($bill->transactions->count()) || (!empty($bill->transactions->count()) && $bill->paid != $bill->amount))
                                                        <button @click="onPayment" id="button-payment" class="btn btn-success btn-sm header-button-bottom">{{ trans('bills.add_payment') }}</button>
                                                    @endif
                                                @stack('timeline_body_make_payment_body_button_payment_end')
                                            </div>
                                        @stack('timeline_body_make_payment_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_make_payment_end')
                        </div>
                    </div>
                </div>
            @stack('timeline_body_end')
        @endif
    @stack('timeline_end')

    @stack('bill_start')
        <div class="card">
            @stack('bill_status_start')
                <div class="card-header status-{{ $bill->status_label }}">
                    <h3 class="text-white mb-0 float-right">{{ trans('bills.statuses.' . $bill->status) }}</h3>
                </div>
            @stack('bill_status_end')

            <div class="card-body">
                @stack('bill_header_start')
                    <div class="row mx--4">
                        <div class="col-md-7 border-bottom-1">
                            <div class="table-responsive mt-2">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                @if (!empty($bill->contact->logo) && !empty($bill->contact->logo->id))
                                                    <img src="{{ Storage::url($bill->contact->logo->id) }}" height="128" width="128" alt="{{ $bill->contact_name }}"/>
                                                @else
                                                    <img src="{{ $logo }}" alt="{{ $bill->contact_name }}"/>
                                                @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 border-bottom-1">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ setting('company.name') }}
                                            </th>
                                        </tr>
                                        @if (setting('company.address'))
                                            <tr>
                                                <th>
                                                    {!! nl2br(setting('company.address')) !!}
                                                </th>
                                            </tr>
                                        @endif
                                        @if (setting('company.tax_number'))
                                            <tr>
                                                <th>
                                                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                                                </th>
                                            </tr>
                                        @endif
                                        @if (setting('company.phone'))
                                            <tr>
                                                <th>
                                                    {{ setting('company.phone') }}
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>
                                                {{ setting('company.email') }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_header_end')

                @stack('bill_information_start')
                    <div class="row">
                        <div class="col-md-7 long-texts">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ trans('bills.bill_from') }}
                                                @stack('name_input_start')
                                                    <strong class="d-block">{{ $bill->contact_name }}</strong>
                                                @stack('name_input_end')
                                            </th>
                                        </tr>
                                        @if ($bill->contact_address || $__env->hasStack('address_input_start', 'address_input_end'))
                                            <tr>
                                                <th>
                                                    @stack('address_input_start')
                                                        @if ($bill->contact_address)
                                                            {!! nl2br($bill->contact_address) !!}
                                                        @endif
                                                    @stack('address_input_end')
                                                </th>
                                            </tr>
                                        @endif
                                        @if ($bill->contact_tax_number || $__env->hasStack('tax_number_input_start', 'tax_number_input_end'))
                                            <tr>
                                                <th>
                                                    @stack('tax_number_input_start')
                                                        @if ($bill->contact_tax_number)
                                                            {{ trans('general.tax_number') }}: {{ $bill->contact_tax_number }}
                                                        @endif
                                                    @stack('tax_number_input_end')
                                                </th>
                                            </tr>
                                        @endif
                                        @if ($bill->contact_phone || $__env->hasStack('phone_input_start', 'phone_input_end'))
                                            <tr>
                                                <th>
                                                    @stack('phone_input_start')
                                                        @if ($bill->contact_phone)
                                                            {{ $bill->contact_phone }}
                                                        @endif
                                                    @stack('phone_input_end')
                                                </th>
                                            </tr>
                                        @endif
                                        @if ($bill->contact_email || $__env->hasStack('email_start', 'email_input_end'))
                                            <tr>
                                                <th>
                                                    @stack('email_start')
                                                        @if ($bill->contact_email)
                                                            {{ $bill->contact_email }}
                                                        @endif
                                                    @stack('email_input_end')
                                                </th>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 long-texts">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        @stack('bill_number_input_start')
                                            <tr>
                                                <th>{{ trans('bills.bill_number') }}:</th>
                                                <td class="text-right">{{ $bill->bill_number }}</td>
                                            </tr>
                                        @stack('bill_number_input_end')

                                        @stack('order_number_input_start')
                                            @if ($bill->order_number)
                                                <tr>
                                                    <th>{{ trans('bills.order_number') }}:</th>
                                                    <td class="text-right">{{ $bill->order_number }}</td>
                                                </tr>
                                            @endif
                                        @stack('order_number_input_end')

                                        @stack('billed_at_input_start')
                                            <tr>
                                                <th>{{ trans('bills.bill_date') }}:</th>
                                                <td class="text-right">@date($bill->billed_at)</td>
                                            </tr>
                                        @stack('billed_at_input_end')

                                        @stack('due_at_input_start')
                                            <tr>
                                                <th>{{ trans('bills.payment_due') }}:</th>
                                                <td class="text-right">@date($bill->due_at)</td>
                                            </tr>
                                        @stack('due_at_input_end')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_information_end')

                @stack('bill_item_start')
                    <div class="row show-table">
                        <div class="col-md-12">
                            <div class="table-responsive overflow-y-hidden">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr class="d-flex flex-nowrap">
                                            @stack('name_th_start')
                                                <th class="col-xs-4 col-sm-5 pl-5">{{ trans_choice('general.items', 1) }}</th>
                                            @stack('name_th_end')

                                            @stack('quantity_th_start')
                                                <th class="col-xs-4 col-sm-1 text-center">{{ trans('bills.quantity') }}</th>
                                            @stack('quantity_th_end')

                                            @stack('price_th_start')
                                                <th class="col-sm-3 text-right d-none d-sm-block">{{ trans('bills.price') }}</th>
                                            @stack('price_th_end')

                                            @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                                @stack('discount_th_start')
                                                    <th class="col-sm-1 text-center d-none d-sm-block">{{ trans('bills.discount') }}</th>
                                                @stack('discount_th_end')
                                            @endif

                                            @stack('total_th_start')
                                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('bills.total') }}</th>
                                            @stack('total_th_end')
                                        </tr>
                                        @foreach($bill->items as $item)
                                            @include('partials.documents.item.show', ['document' => $bill])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_item_end')

                @stack('bill_total_start')
                    <div class="row mt-5">
                        <div class="col-md-7">
                            @stack('notes_input_start')
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if ($bill->notes)
                                                <tr>
                                                    <th>
                                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                                        <p class="text-muted long-texts">{!! nl2br($bill->notes) !!}</p>
                                                    </th>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @stack('notes_input_end')
                        </div>
                        <div class="col-md-5">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach ($bill->totals_sorted as $total)
                                            @if ($total->code != 'total')
                                                @stack($total->code . '_total_tr_start')
                                                <tr>
                                                    <th>{{ trans($total->title) }}:</th>
                                                    <td class="text-right">@money($total->amount, $bill->currency_code, true)</td>
                                                </tr>
                                                @stack($total->code . '_total_tr_end')
                                            @else
                                                @if ($bill->paid)
                                                    @stack('paid_total_tr_start')
                                                    <tr>
                                                        <th class="text-success">
                                                            {{ trans('bills.paid') }}:
                                                        </th>
                                                        <td class="text-success text-right">- @money($bill->paid, $bill->currency_code, true)</td>
                                                    </tr>
                                                    @stack('paid_total_tr_end')
                                                @endif
                                                @stack('grand_total_tr_start')
                                                <tr>
                                                    <th>{{ trans($total->name) }}:</th>
                                                    <td class="text-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                                                </tr>
                                                @stack('grand_total_tr_end')
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('bill_total_end')
            </div>

            @stack('box_footer_start')
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-xs-12 col-sm-4">
                            @if($bill->attachment)
                                @php $file = $bill->attachment; @endphp
                                @include('partials.media.file')
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-8 text-right">
                            @stack('button_edit_start')
                            @if(!$bill->reconciled)
                                <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-info header-button-top">
                                    <i class="fas fa-edit"></i>&nbsp; {{ trans('general.edit') }}
                                </a>
                            @endif
                            @stack('button_edit_end')

                            @stack('button_print_start')
                            <a href="{{ route('bills.print', $bill->id) }}" target="_blank" class="btn btn-success header-button-top">
                                <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                            </a>
                            @stack('button_print_end')

                            @stack('button_group_start')
                            <div class="dropup header-drop-top">
                                <button type="button" class="btn btn-primary header-button-top" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>

                                <div class="dropdown-menu" role="menu">
                                    @stack('button_dropdown_start')
                                    @if ($bill->status != 'cancelled')
                                        @if ($bill->status != 'paid')
                                            @stack('button_pay_start')
                                            @permission('update-purchases-bills')
                                                <a class="dropdown-item" href="{{ route('bills.paid', $bill->id) }}">{{ trans('bills.mark_paid') }}</a>
                                            @endpermission

                                            @if (empty($bill->paid) || ($bill->paid != $bill->amount))
                                                <button class="dropdown-item" id="button-payment" @click="onPayment">{{ trans('bills.add_payment') }}</button>
                                            @endif
                                            @stack('button_pay_end')
                                            <div class="dropdown-divider"></div>
                                        @endif

                                        @stack('button_dropdown_divider_1')

                                        @permission('update-purchases-bills')
                                            @stack('button_received_start')
                                            @if ($bill->status == 'draft')
                                                <a class="dropdown-item" href="{{ route('bills.received', $bill->id) }}">{{ trans('bills.mark_received') }}</a></a>
                                            @else
                                                <button type="button" class="dropdown-item" disabled="disabled">{{ trans('bills.mark_received') }}</button>
                                            @endif
                                            @stack('button_received_end')
                                        @endpermission
                                    @endif

                                    @stack('button_pdf_start')
                                    <a class="dropdown-item" href="{{ route('bills.pdf', $bill->id) }}">{{ trans('bills.download_pdf') }}</a>
                                    @stack('button_pdf_end')

                                    @permission('update-purchases-bills')
                                        @if ($bill->status != 'cancelled')
                                            @stack('button_cancelled_start')
                                            <a class="dropdown-item" href="{{ route('bills.cancelled', $bill->id) }}">{{ trans('general.cancel') }}</a>
                                            @stack('button_cancelled_end')
                                        @endif
                                    @endpermission

                                    @stack('button_dropdown_divider_2')

                                    @permission('delete-purchases-bills')
                                        @if (!$bill->reconciled)
                                            @stack('button_delete_start')
                                            {!! Form::deleteLink($bill, 'purchases/bills') !!}
                                            @stack('button_delete_end')
                                        @endif
                                    @endpermission
                                    @stack('button_dropdown_end')
                                </div>
                            </div>
                            @stack('button_group_end')
                        </div>
                    </div>
                </div>
            @stack('box_footer_end')
        </div>
    @stack('bill_end')

    @stack('row_footer_start')
        <div class="row">
            @stack('row_footer_histories_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="accordion-histories-header" data-toggle="collapse" data-target="#accordion-histories-body" aria-expanded="false" aria-controls="accordion-histories-body">
                                <h4 class="mb-0">{{ trans('bills.histories') }}</h4>
                            </div>
                            <div id="accordion-histories-body" class="collapse hide" aria-labelledby="accordion-histories-header">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover">
                                        <thead class="thead-light">
                                            @stack('row_footer_histories_head_tr_start')
                                            <tr class="row table-head-line">
                                                @stack('row_footer_histories_head_td_start')
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.date') }}</th>
                                                <th class="col-xs-4 col-sm-3 text-left">{{ trans_choice('general.statuses', 1) }}</th>
                                                <th class="col-xs-4 col-sm-6 text-left long-texts">{{ trans('general.description') }}</th>
                                                @stack('row_footer_histories_head_td_end')
                                            </tr>
                                            @stack('row_footer_histories_head_tr_end')
                                        </thead>
                                        <tbody>
                                            @stack('row_footer_histories_body_tr_start')
                                            @foreach($bill->histories as $history)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    @stack('row_footer_histories_body_td_start')
                                                    <td class="col-xs-4 col-sm-3">@date($history->created_at)</td>
                                                    <td class="col-xs-4 col-sm-3 text-left">{{ trans('bills.statuses.' . $history->status) }}</td>
                                                    <td class="col-xs-4 col-sm-6 text-left long-texts">{{ $history->description }}</td>
                                                    @stack('row_footer_histories_body_td_end')
                                                </tr>
                                            @endforeach
                                            @stack('row_footer_histories_body_tr_end')
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_histories_end')

            @stack('row_footer_transactions_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="accordion-transactions-header" data-toggle="collapse" data-target="#accordion-transactions-body" aria-expanded="false" aria-controls="accordion-transactions-body">
                                <h4 class="mb-0">{{ trans_choice('general.transactions', 2) }}</h4>
                            </div>
                            <div id="accordion-transactions-body" class="collapse hide" aria-labelledby="accordion-transactions-header">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover">
                                        <thead class="thead-light">
                                            @stack('row_footer_transactions_head_tr_start')
                                            <tr class="row table-head-line">
                                                @stack('row_footer_transactions_head_td_start')
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.date') }}</th>
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block">{{ trans_choice('general.accounts', 1) }}</th>
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.actions') }}</th>
                                                @stack('row_footer_transactions_head_td_end')
                                            </tr>
                                            @stack('row_footer_transactions_head_tr_end')
                                        </thead>
                                        <tbody>
                                            @stack('row_footer_transactions_body_tr_start')
                                            @if ($bill->transactions->count())
                                                @foreach($bill->transactions as $transaction)
                                                    <tr class="row align-items-center border-top-1 tr-py">
                                                        @stack('row_footer_transactions_body_td_start')
                                                        <td class="col-xs-4 col-sm-3">@date($transaction->paid_at)</td>
                                                        <td class="col-xs-4 col-sm-3">@money($transaction->amount, $transaction->currency_code, true)</td>
                                                        <td class="col-sm-3 d-none d-sm-block">{{ $transaction->account->name }}</td>
                                                        <td class="col-xs-4 col-sm-3 py-0">
                                                            @if ($transaction->reconciled)
                                                                <button type="button" class="btn btn-default btn-sm">
                                                                    {{ trans('reconciliations.reconciled') }}
                                                                </button>
                                                            @else
                                                                @php $message = trans('general.delete_confirm', [
                                                                    'name' => '<strong>' . Date::parse($transaction->paid_at)->format($date_format) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . ' - ' . $transaction->account->name . '</strong>',
                                                                    'type' => strtolower(trans_choice('general.transactions', 1))
                                                                    ]);
                                                                @endphp

                                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                                                    'type'    => 'button',
                                                                    'class'   => 'btn btn-danger btn-sm',
                                                                    'title'   => trans('general.delete'),
                                                                    '@click'  => 'confirmDelete("' . route('transactions.destroy', $transaction->id) . '", "' . trans_choice('general.transactions', 2) . '", "' . $message. '",  "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                                                )) !!}
                                                            @endif
                                                        </td>
                                                        @stack('row_footer_transactions_body_td_end')
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-muted nr-py" id="datatable-basic_info" role="status" aria-live="polite">
                                                            {{ trans('general.no_records') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                            @stack('row_footer_transactions_body_tr_end')
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_transactions_end')
        </div>
    @stack('row_footer_end')

    {{ Form::hidden('bill_id', $bill->id, ['id' => 'bill_id']) }}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/purchases/bills.js?v=' . version('short')) }}"></script>
@endpush
