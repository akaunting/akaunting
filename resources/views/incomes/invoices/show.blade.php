@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    @stack('recurring_message_start')
        @if (($recurring = $invoice->recurring) && ($next = $recurring->next()))
            <div class="callout callout-info">
                @stack('recurring_message_head_start')
                    <h4>{{ trans('recurring.recurring') }}</h4>
                @stack('recurring_message_head_end')

                @stack('recurring_message_body_start')
                    <p>{{ trans('recurring.message', [
                        'type' => mb_strtolower(trans_choice('general.invoices', 1)),
                        'date' => $next->format($date_format)
                    ]) }}
                    </p>
                @stack('recurring_message_body_end')
            </div>
        @endif
    @stack('recurring_message_end')

    @stack('status_message_start')
        @if ($invoice->status->code == 'draft')
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-warning fade show" role="alert">
                        @stack('status_message_body_start')
                            <span class="alert-text">
                                <strong>{!! trans('invoices.messages.draft') !!}</strong>
                            </span>
                        @stack('status_message_body_end')
                    </div>
                </div>
            </div>
        @endif
    @stack('status_message_end')

    @stack('timeline_start')
        @if ($invoice->status->code != 'paid')
            @stack('timeline_body_start')
                <div class="card">
                    <div class="card-body">
                        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                            @stack('timeline_body_create_invoice_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-info">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <div class="timeline-content">
                                        @stack('timeline_body_create_invoice_head_start')
                                            <h3 class="font-weight-bold">{{ trans('invoices.create_invoice') }}</h3>
                                        @stack('timeline_body_create_invoice_head_end')

                                        @stack('timeline_body_create_invoice_body_start')
                                            @stack('timeline_body_create_invoice_body_message_start')
                                                <span> {{ trans_choice('general.statuses', 1) .  ' :'  }}</span> <span class=" text-sm font-weight-300">{{ trans('invoices.messages.status.created', ['date' => Date::parse($invoice->created_at)->format($date_format)]) }}</span>
                                            @stack('timeline_body_create_invoice_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_create_invoice_body_button_edit_start')
                                                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/edit') }}" class="btn btn-info btn-sm btn-alone">
                                                        {{ trans('general.edit') }}
                                                    </a>
                                                @stack('timeline_body_create_invoice_body_button_edit_end')
                                            </div>
                                        @stack('timeline_body_create_invoice_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_create_invoice_end')

                            @stack('timeline_body_send_invoice_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-warning">
                                        <i class="far fa-envelope"></i>
                                    </span>
                                    <div class="timeline-content">
                                        @stack('timeline_body_send_invoice_head_start')
                                            <h3 class="font-weight-bold">{{ trans('invoices.send_invoice') }}</h3>
                                        @stack('timeline_body_send_invoice_head_end')

                                        @stack('timeline_body_send_invoice_body_start')
                                            @if ($invoice->status->code != 'sent' && $invoice->status->code != 'partial' && $invoice->status->code != 'viewed')
                                                @stack('timeline_body_send_invoice_body_message_start')
                                                    <span>{{ trans_choice('general.statuses', 1) . ' :' }}</span> <span class=" text-sm font-weight-300">{{ trans('invoices.messages.status.send.draft') }}</span>
                                                @stack('timeline_body_send_invoice_body_message_end')

                                                <div class="mt-3">
                                                    @stack('timeline_body_send_invoice_body_button_sent_start')
                                                        @permission('update-incomes-invoices')
                                                            @if($invoice->invoice_status_code == 'draft')
                                                                <a href="{{ url('incomes/invoices/' . $invoice->id . '/sent') }}" class="btn btn-white btn-sm header-button-top">{{ trans('invoices.mark_sent') }}</a>
                                                            @else
                                                                <button type="button" class="btn btn-secondary btn-sm header-button-top" disabled="disabled">
                                                                    <span class="text-disabled">{{ trans('invoices.mark_sent') }}</span>
                                                                </button>
                                                            @endif
                                                        @endpermission
                                                    @stack('timeline_body_send_invoice_body_button_sent_end')

                                                    @stack('timeline_body_send_invoice_body_button_email_start')
                                                        @if($invoice->contact_email)
                                                            <a href="{{ url('incomes/invoices/' . $invoice->id . '/email') }}" class="btn btn-warning btn-sm header-button-bottom">{{ trans('invoices.send_mail') }}</a>
                                                        @else
                                                            <button type="button" class="btn btn-white btn-sm header-button-bottom green-tooltip" disabled="disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}">
                                                                <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                                                            </button>
                                                        @endif
                                                    @stack('timeline_body_send_invoice_body_button_email_end')
                                                </div>

                                            @elseif($invoice->status->code == 'viewed')
                                                @stack('timeline_body_viewed_invoice_body_message_start')
                                                    <span>{{ trans_choice('general.statuses', 1) . ' :' }}</span>  <span class=" text-sm font-weight-300">{{ trans('invoices.messages.status.viewed') }}</span>
                                                @stack('timeline_body_viewed_invoice_body_message_end')
                                            @else
                                                @stack('timeline_body_send_invoice_body_message_start')
                                                    <span>{{ trans_choice('general.statuses', 1) . ' :' }}</span>  <span class=" text-sm font-weight-300">{{ trans('invoices.messages.status.send.sent', ['date' => Date::parse($invoice->created_at)->format($date_format)]) }}</span>
                                                @stack('timeline_body_send_invoice_body_message_end')
                                            @endif
                                        @stack('timeline_body_send_invoice_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_send_invoice_end')

                            @stack('timeline_body_get_paid_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-success">
                                        <i class="far fa-money-bill-alt"></i>
                                    </span>

                                    <div class="timeline-content">
                                        @stack('timeline_body_get_paid_head_start')
                                            <h3 class="font-weight-bold">{{ trans('invoices.get_paid') }}</h3>
                                        @stack('timeline_body_get_paid_head_end')

                                        @stack('timeline_body_get_paid_body_start')
                                            @stack('timeline_body_get_paid_body_message_start')
                                                @if($invoice->status->code != 'paid' && empty($invoice->transactions->count()))
                                                    <span>{{ trans_choice('general.statuses', 1) . ' :' }}</span> <span class=" text-sm font-weight-300">{{ trans('invoices.messages.status.paid.await') }}</span>
                                                @else
                                                    <span>{{ trans_choice('general.statuses', 1) . ' :' }}</span> <span class=" text-sm font-weight-300">{{ trans('general.partially_paid') }}</span>
                                                @endif
                                            @stack('timeline_body_get_paid_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_get_paid_body_button_pay_start')
                                                    @permission('update-incomes-invoices')
                                                        <a href="{{ url('incomes/invoices/' . $invoice->id . '/pay') }}" class="btn btn-white btn-sm header-button-top">{{ trans('invoices.mark_paid') }}</a>
                                                    @endpermission
                                                @stack('timeline_body_get_paid_body_button_pay_end')

                                                @stack('timeline_body_get_paid_body_button_payment_start')
                                                    @if(empty($invoice->transactions->count()) || (!empty($invoice->transactions->count()) && $invoice->paid != $invoice->amount))
                                                        <button @click="onPayment" id="button-payment" class="btn btn-success btn-sm header-button-bottom">{{ trans('invoices.add_payment') }}</button>
                                                    @endif
                                                @stack('timeline_body_get_paid_body_button_payment_end')
                                            </div>
                                        @stack('timeline_body_get_paid_body_end')
                                    </div>
                                </div>
                            @stack('timeline_body_get_paid_end')
                        </div>
                    </div>
                </div>
            @stack('timeline_body_get_paid_end')
        @endif
    @stack('timeline_end')

    @stack('invoice_start')
        <div class="card">
            @stack('invoice_status_start')
                <div class="card-header status-{{ $invoice->status->label }}">
                    <h3 class="text-white mb-0 float-right">{{ trans('invoices.status.' . $invoice->status->code) }}</h3>
                </div>
            @stack('invoice_status_end')

            <div class="card-body">
                @stack('invoice_header_start')
                    <div class="row mx--4">
                        <div class="col-md-7 border-bottom-1">
                            <div class="table-responsive mt-2">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                @if (setting('company.logo'))
                                                    <img src="{{ Storage::url(setting('company.logo')) }}"/>
                                                @else
                                                    <span class="avatar avatar-size rounded-circle bg-default">
                                                        <i class="fas fa-building"></i>
                                                    </span>
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
                                        <tr>
                                            <th>
                                                {!! nl2br(setting('company.address')) !!}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @if (setting('company.tax_number'))
                                                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @if (setting('company.phone'))
                                                    {{ setting('company.phone') }}
                                                @endif
                                            </th>
                                        </tr>
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
                @stack('invoice_header_end')

                @stack('invoice_information_start')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ trans('invoices.bill_to') }}
                                                @stack('name_input_start')
                                                    <strong class="d-block">{{ $invoice->contact_name }}</strong>
                                                @stack('name_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('address_input_start')
                                                {!! nl2br($invoice->contact_address) !!}
                                                @stack('address_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('tax_number_input_start')
                                                    @if ($invoice->contact_tax_number)
                                                        {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}
                                                    @endif
                                                @stack('tax_number_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('phone_input_start')
                                                    @if ($invoice->contact_phone)
                                                        {{ $invoice->contact_phone }}
                                                    @endif
                                                @stack('phone_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('email_start')
                                                    {{ $invoice->contact_email }}
                                                @stack('email_input_end')
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        @stack('invoice_number_input_start')
                                            <tr>
                                                <th>{{ trans('invoices.invoice_number') }}:</th>
                                                <td class="text-right">{{ $invoice->invoice_number }}</td>
                                            </tr>
                                        @stack('invoice_number_input_end')

                                        @stack('order_number_input_start')
                                            @if ($invoice->order_number)
                                                <tr>
                                                    <th>{{ trans('invoices.order_number') }}:</th>
                                                    <td class="text-right">{{ $invoice->order_number }}</td>
                                                </tr>
                                            @endif
                                        @stack('order_number_input_end')

                                        @stack('invoiced_at_input_start')
                                            <tr>
                                                <th>{{ trans('invoices.invoice_date') }}:</th>
                                                <td class="text-right">@date($invoice->invoiced_at)</td>
                                            </tr>
                                        @stack('invoiced_at_input_end')

                                        @stack('due_at_input_start')
                                            <tr>
                                                <th>{{ trans('invoices.payment_due') }}:</th>
                                                <td class="text-right">@date($invoice->due_at)</td>
                                            </tr>
                                        @stack('due_at_input_end')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('invoice_information_end')

                @stack('invoice_item_start')
                    <div class="row show-table">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr class="row">
                                            @stack('name_th_start')
                                                <th class="col-xs-4 col-sm-3 pl-5">{{ trans_choice($text_override['items'], 2) }}</th>
                                            @stack('name_th_end')

                                            @stack('quantity_th_start')
                                                <th class="col-xs-4 col-sm-3 text-center">{{ trans($text_override['quantity']) }}</th>
                                            @stack('quantity_th_end')

                                            @stack('price_th_start')
                                                <th class="col-sm-3 text-center hidden-sm pl-5">{{ trans($text_override['price']) }}</th>
                                            @stack('price_th_end')

                                            @stack('total_th_start')
                                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('invoices.total') }}</th>
                                            @stack('total_th_end')
                                        </tr>
                                        @foreach($invoice->items as $item)
                                            <tr class="row">
                                                @stack('name_td_start')
                                                    <td class="col-xs-4 col-sm-3 pl-5">{{ $item->name }}</td>
                                                @stack('name_td_end')

                                                @stack('quantity_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-center">{{ $item->quantity }}</td>
                                                @stack('quantity_td_end')

                                                @stack('price_td_start')
                                                    <td class="col-sm-3 text-center hidden-sm pl-5">@money($item->price, $invoice->currency_code, true)</td>
                                                @stack('price_td_end')

                                                @stack('total_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($item->total, $invoice->currency_code, true)</td>
                                                @stack('total_td_end')
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('invoice_item_end')

                @stack('invoice_total_start')
                    <div class="row">
                        <div class="col-md-7">
                            @stack('notes_input_start')
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if ($invoice->notes)
                                                <tr>
                                                    <th>
                                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                                        <p class="form-control text-muted show-note">{{ $invoice->notes }}</p>
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
                                        @foreach ($invoice->totals as $total)
                                            @if ($total->code != 'total')
                                                @stack($total->code . '_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->title) }}:</th>
                                                        <td class="text-right">@money($total->amount, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @stack($total->code . '_td_end')
                                            @else
                                                @if ($invoice->paid)
                                                    <tr class="text-success">
                                                        <th>{{ trans('invoices.paid') }}:</th>
                                                        <td class="text-right">- @money($invoice->paid, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @endif
                                                @stack('grand_total_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->name) }}:</th>
                                                        <td class="text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @stack('grand_total_td_end')
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('invoice_total_end')
            </div>

            @stack('card_footer_start')
                <div class="card-footer">
                    <div class="float-right">
                        @stack('button_edit_start')
                            @if(!$invoice->reconciled)
                                <a href="{{ url('incomes/invoices/' . $invoice->id . '/edit') }}" class="btn btn-info header-button-top">
                                    <i class="fas fa-edit"></i>&nbsp; {{ trans('general.edit') }}
                                </a>
                            @endif
                        @stack('button_edit_end')

                        @stack('button_print_start')
                            <a href="{{ url('incomes/invoices/' . $invoice->id . '/print') }}" target="_blank" class="btn btn-success header-button-top">
                                <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                            </a>
                        @stack('button_print_end')

                        @stack('button_share_start')
                            <a href="{{ $signed_url }}" target="_blank" class="btn btn-white header-button-top">
                                <i class="fa fa-share"></i>&nbsp; Share
                            </a>
                        @stack('button_share_end')

                        @stack('button_group_start')
                            <div class="dropup">
                                <button type="button" class="btn btn-primary header-button-top" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                                <div class="dropdown-menu" role="menu">
                                    @stack('button_pay_start')
                                        @if($invoice->status->code != 'paid')
                                            @permission('update-incomes-invoices')
                                                <a class="dropdown-item" href="{{ url('incomes/invoices/' . $invoice->id . '/pay') }}">{{ trans('invoices.mark_paid') }}</a>
                                            @endpermission

                                            @if(empty($invoice->paid) || ($invoice->paid != $invoice->amount))
                                                <button class="dropdown-item" id="button-payment" @click="onPayment">{{ trans('invoices.add_payment') }}</button>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                        @endif
                                    @stack('button_pay_end')

                                    @stack('button_sent_start')
                                        @permission('update-incomes-invoices')
                                            @if($invoice->invoice_status_code == 'draft')
                                                <a class="dropdown-item" href="{{ url('incomes/invoices/' . $invoice->id . '/sent') }}">{{ trans('invoices.mark_sent') }}</a>
                                            @else
                                                <button type="button" class="dropdown-item" disabled="disabled"><span class="text-disabled">{{ trans('invoices.mark_sent') }}</span></button>
                                            @endif
                                        @endpermission
                                    @stack('button_sent_end')

                                    @stack('button_email_start')
                                        @if($invoice->contact_email)
                                            <a class="dropdown-item" href="{{ url('incomes/invoices/' . $invoice->id . '/email') }}">{{ trans('invoices.send_mail') }}</a>
                                        @else
                                            <button type="button" class="dropdown-item" disabled="disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}">
                                                <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                                            </button>
                                        @endif
                                    @stack('button_email_end')

                                    @stack('button_pdf_start')
                                        <a class="dropdown-item" href="{{ url('incomes/invoices/' . $invoice->id . '/pdf') }}">{{ trans('invoices.download_pdf') }}</a>
                                    @stack('button_pdf_end')

                                    @stack('button_delete_start')
                                        @permission('delete-incomes-invoices')
                                            @if(!$invoice->reconciled)
                                                {!! Form::deleteLink($invoice, 'incomes/invoices') !!}
                                            @endif
                                        @endpermission
                                    @stack('button_delete_end')
                                </div>
                            </div>
                        @stack('button_group_end')

                        @if($invoice->attachment)
                            @php $file = $invoice->attachment; @endphp
                            @include('partials.media.file')
                        @endif
                    </div>
                </div>
            @stack('card_footer_end')
        </div>
    @stack('invoice_end')

    @stack('row_footer_start')
        <div class="row">
            @stack('row_footer_history_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                <h4 class="mb-0">{{ trans('invoices.histories') }}</h4>
                            </div>
                            <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr class="table-head-line">
                                                <th>{{ trans('general.date') }}</th>
                                                <th class="text-center">{{ trans_choice('general.statuses', 1) }}</th>
                                                <th class="text-center">{{ trans('general.description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->histories as $history)
                                                <tr>
                                                    <td>@date($history->created_at)</td>
                                                    <td class="text-center">{{ $history->status->name }}</td>
                                                    <td class="text-center">{{ $history->description }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_history_end')

            @stack('row_footer_payment_start')
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <h4 class="mb-0">{{ trans_choice('general.transactions', 2) }}</h4>
                            </div>
                            <div id="collapseTwo" class="collapse hide" aria-labelledby="headingTwo">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-light">
                                            <tr class="table-head-line">
                                                <th>{{ trans('general.date') }}</th>
                                                <th>{{ trans('general.amount') }}</th>
                                                <th>{{ trans_choice('general.accounts', 1) }}</th>
                                                <th>{{ trans('general.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($invoice->transactions->count())
                                                @foreach($invoice->transactions as $transaction)
                                                    <tr>
                                                        <td>@date($transaction->paid_at)</td>
                                                        <td>@money($transaction->amount, $transaction->currency_code, true)</td>
                                                        <td>{{ $transaction->account->name }}</td>
                                                        <td>
                                                            @if ($transaction->reconciled)
                                                                <button type="button" class="btn btn-default btn-xs">
                                                                    <i class="fa fa-check"></i> {{ trans('reconciliations.reconciled') }}
                                                                </button>
                                                            @else
                                                                {!! Form::open([
                                                                    'id' => 'invoice-transaction-' . $transaction->id,
                                                                    'method' => 'DELETE',
                                                                    'route' => ['transactions.destroy', $transaction->id],
                                                                    'class' => 'd-inline'
                                                                ]) !!}
                                                                {{ Form::hidden('form_id', '#invoice-transaction-' . $transaction->id, ['id' => 'form_id-' . $transaction->id]) }}
                                                                {{ Form::hidden('title', trans_choice('general.transactions', 2), ['id' => 'title-' . $transaction->id]) }}
                                                                @php $message = trans('general.delete_confirm', [
                                                                    'name' => '<strong>' . Date::parse($transaction->paid_at)->format($date_format) . ' - ' . money($transaction->amount, $transaction->currency_code, true) . ' - ' . $transaction->account->name . '</strong>',
                                                                    'type' => strtolower(trans_choice('general.transactions', 1))
                                                                    ]);
                                                                @endphp
                                                                {{ Form::hidden('message', $message, ['id' => 'mesage-' . $transaction->id]) }}
                                                                {{ Form::hidden('cancel', trans('general.cancel'), ['id' => 'cancel-' . $transaction->id]) }}
                                                                {{ Form::hidden('delete', trans('general.delete'), ['id' => 'delete-' . $transaction->id]) }}

                                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                                                    'type'    => 'button',
                                                                    'class'   => 'btn btn-danger btn-sm',
                                                                    'title'   => trans('general.delete'),
                                                                    '@click'  => 'onDeleteTransaction("invoice-transaction-' . $transaction->id . '")'
                                                                )) !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="4">
                                                        <div class="text-muted" id="datatable-basic_info" role="status" aria-live="polite">
                                                            {{ trans('general.no_records') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_payment_end')
        </div>
    @stack('row_footer_end')
@endsection

@push('content_content_end')
    <akaunting-modal
        :show="payment.modal"
        :title="'{{ trans('general.title.new', ['type' => trans_choice('general.payments', 1)]) }}'"
        :message="payment.html"
        :button_cancel="'{{ trans('general.button.save') }}'"
        :button_delete="'{{ trans('general.button.cancel') }}'">
        <template #modal-body>
            @include('modals.invoices.payment')
        </template>

        <template #card-footer>
            <div class="float-right">
                <button type="button" class="btn btn-outline-secondary" @click="closePayment">
                    <span>{{ trans('general.cancel') }}</span>
                </button>

                <a href="{{ url('apps/categories/payment-gateway') }}" class="btn btn-white">
                    <span class="fa fa-money"></span> &nbsp;{{ trans('invoices.accept_payments') }}
                </a>

                <button type="button" class="btn btn-success button-submit" @click="addPayment">
                    <div class="aka-loader d-none"></div>
                    <span>{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@endpush

@push('scripts_start')
    <script src="{{ asset('public/js/incomes/invoices.js?v=' . version('short')) }}"></script>
@endpush
