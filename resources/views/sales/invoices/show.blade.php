@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    @stack('recurring_message_start')
        @if (($recurring = $invoice->recurring) && ($next = $recurring->next()))
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
                                        'type' => mb_strtolower(trans_choice('general.invoices', 1)),
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
        @if ($invoice->status == 'draft')
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-danger fade show" role="alert">
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
        @if (!in_array($invoice->status, ['paid', 'cancelled']))
            @stack('timeline_body_start')
                <div class="card">
                    <div class="card-body">
                        <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                            @stack('timeline_body_create_invoice_start')
                                <div class="timeline-block">
                                    <span class="timeline-step badge-primary">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <div class="timeline-content">
                                        @stack('timeline_body_create_invoice_head_start')
                                            <h2 class="font-weight-500">{{ trans('invoices.create_invoice') }}</h2>
                                        @stack('timeline_body_create_invoice_head_end')

                                        @stack('timeline_body_create_invoice_body_start')
                                            @stack('timeline_body_create_invoice_body_message_start')
                                                <small>{{ trans_choice('general.statuses', 1) .  ':'  }}</small>
                                                <small>{{ trans('invoices.messages.status.created', ['date' => Date::parse($invoice->created_at)->format($date_format)]) }}</small>
                                            @stack('timeline_body_create_invoice_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_create_invoice_body_button_edit_start')
                                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-primary btn-sm btn-alone">
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
                                    <span class="timeline-step badge-danger">
                                        <i class="far fa-envelope"></i>
                                    </span>
                                    <div class="timeline-content">
                                        @stack('timeline_body_send_invoice_head_start')
                                            <h2 class="font-weight-500">{{ trans('invoices.send_invoice') }}</h2>
                                        @stack('timeline_body_send_invoice_head_end')

                                        @stack('timeline_body_send_invoice_body_start')
                                            @if ($invoice->status != 'sent' && $invoice->status != 'partial' && $invoice->status != 'viewed')
                                                @stack('timeline_body_send_invoice_body_message_start')
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('invoices.messages.status.send.draft') }}</small>
                                                @stack('timeline_body_send_invoice_body_message_end')

                                                <div class="mt-3">
                                                    @stack('timeline_body_send_invoice_body_button_sent_start')
                                                        @permission('update-sales-invoices')
                                                            @if($invoice->status == 'draft')
                                                                <a href="{{ route('invoices.sent', $invoice->id) }}" class="btn btn-white btn-sm header-button-top">{{ trans('invoices.mark_sent') }}</a>
                                                            @else
                                                                <button type="button" class="btn btn-secondary btn-sm header-button-top" disabled="disabled">
                                                                    <span class="text-disabled">{{ trans('invoices.mark_sent') }}</span>
                                                                </button>
                                                            @endif
                                                        @endpermission
                                                    @stack('timeline_body_send_invoice_body_button_sent_end')

                                                    @stack('timeline_body_send_invoice_body_button_email_start')
                                                        @if($invoice->contact_email)
                                                            <a href="{{ route('invoices.email', $invoice->id) }}" class="btn btn-danger btn-sm header-button-bottom">{{ trans('invoices.send_mail') }}</a>
                                                        @else
                                                            <button type="button" class="btn btn-white btn-sm header-button-bottom green-tooltip" disabled="disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}">
                                                                <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                                                            </button>
                                                        @endif
                                                    @stack('timeline_body_send_invoice_body_button_email_end')
                                                </div>
                                            @elseif($invoice->status == 'viewed')
                                                @stack('timeline_body_viewed_invoice_body_message_start')
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('invoices.messages.status.viewed') }}</small>
                                                @stack('timeline_body_viewed_invoice_body_message_end')
                                            @else
                                                @stack('timeline_body_send_invoice_body_message_start')
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('invoices.messages.status.send.sent', ['date' => Date::parse($invoice->sent_at)->format($date_format)]) }}</small>
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
                                            <h2 class="font-weight-500">{{ trans('invoices.get_paid') }}</h2>
                                        @stack('timeline_body_get_paid_head_end')

                                        @stack('timeline_body_get_paid_body_start')
                                            @stack('timeline_body_get_paid_body_message_start')
                                                @if($invoice->status != 'paid' && empty($invoice->transactions->count()))
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('invoices.messages.status.paid.await') }}</small>
                                                @else
                                                    <small>{{ trans_choice('general.statuses', 1) . ':' }}</small>
                                                    <small>{{ trans('general.partially_paid') }}</small>
                                                @endif
                                            @stack('timeline_body_get_paid_body_message_end')

                                            <div class="mt-3">
                                                @stack('timeline_body_get_paid_body_button_pay_start')
                                                    @permission('update-sales-invoices')
                                                        <a href="{{ route('invoices.paid', $invoice->id) }}" class="btn btn-white btn-sm header-button-top">{{ trans('invoices.mark_paid') }}</a>
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
                <div class="card-header status-{{ $invoice->status_label }}">
                    <h3 class="text-white mb-0 float-right">{{ trans('invoices.statuses.' . $invoice->status) }}</h3>
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
                                                <img src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
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
                        <div class="col-md-7 long-texts">
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
                        <div class="col-md-5 long-texts">
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
                            <div class="table-responsive overflow-y-hidden">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr class="d-flex flex-nowrap">
                                            @stack('name_th_start')
                                                <th class="col-xs-4 col-sm-5 pl-5">{{ trans_choice($text_override['items'], 2) }}</th>
                                            @stack('name_th_end')

                                            @stack('quantity_th_start')
                                                <th class="col-xs-4 col-sm-1 text-center">{{ trans($text_override['quantity']) }}</th>
                                            @stack('quantity_th_end')

                                            @stack('price_th_start')
                                                <th class="col-sm-3 text-right d-none d-sm-block">{{ trans($text_override['price']) }}</th>
                                            @stack('price_th_end')

                                            @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                                @stack('discount_th_start')
                                                    <th class="col-sm-1 text-center d-none d-sm-block">{{ trans('invoices.discount') }}</th>
                                                @stack('discount_th_end')
                                            @endif

                                            @stack('total_th_start')
                                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('invoices.total') }}</th>
                                            @stack('total_th_end')
                                        </tr>
                                        @foreach($invoice->items as $invoice_item)
                                            <tr class="d-flex flex-nowrap">
                                                @stack('name_td_start')
                                                    <td class="col-xs-4 col-sm-5 pl-5">
                                                        {{ $invoice_item->name }}
                                                        @if (!empty($invoice_item->item->description))
                                                            <br><small class="text-pre-nowrap">{!! \Illuminate\Support\Str::limit($invoice_item->item->description, 500) !!}<small>
                                                        @endif
                                                    </td>
                                                @stack('name_td_end')

                                                @stack('quantity_td_start')
                                                    <td class="col-xs-4 col-sm-1 text-center">{{ $invoice_item->quantity }}</td>
                                                @stack('quantity_td_end')

                                                @stack('price_td_start')
                                                    <td class="col-sm-3 text-right d-none d-sm-block">@money($invoice_item->price, $invoice->currency_code, true)</td>
                                                @stack('price_td_end')

                                                @if (in_array(setting('localisation.discount_location', 'total'), ['item', 'both']))
                                                    @stack('discount_td_start')
                                                        <td class="col-sm-1 text-center d-none d-sm-block">{{ $invoice_item->discount }}</td>
                                                    @stack('discount_td_end')
                                                @endif

                                                @stack('total_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($invoice_item->total, $invoice->currency_code, true)</td>
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
                    <div class="row mt-5">
                        <div class="col-md-7">
                            @stack('notes_input_start')
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @if ($invoice->notes)
                                                <tr>
                                                    <th>
                                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                                        <p class="text-muted long-texts">{{ $invoice->notes }}</p>
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
                                        @foreach ($invoice->totals_sorted as $total)
                                            @if ($total->code != 'total')
                                                @stack($total->code . '_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->title) }}:</th>
                                                        <td class="text-right">@money($total->amount, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @stack($total->code . '_td_end')
                                            @else
                                                @if ($invoice->paid)
                                                    <tr>
                                                        <th class="text-success">
                                                            {{ trans('invoices.paid') }}:
                                                        </th>
                                                        <td class="text-success text-right">- @money($invoice->paid, $invoice->currency_code, true)</td>
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
                    <div class="row align-items-center">
                        <div class="col-xs-12 col-sm-4">
                            @if ($invoice->attachment)
                                @php $file = $invoice->attachment; @endphp
                                @include('partials.media.file')
                            @endif
                        </div>

                        <div class="col-xs-12 col-sm-8 text-right">
                            @stack('button_edit_start')
                                @if(!$invoice->reconciled)
                                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-info header-button-top">
                                        <i class="fas fa-edit"></i>&nbsp; {{ trans('general.edit') }}
                                    </a>
                                @endif
                            @stack('button_edit_end')

                            @stack('button_print_start')
                                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="btn btn-success header-button-top">
                                    <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                                </a>
                            @stack('button_print_end')

                            @if ($invoice->status != 'cancelled')
                                @stack('button_share_start')
                                    <a href="{{ $signed_url }}" target="_blank" class="btn btn-white header-button-top">
                                        <i class="fa fa-share"></i>&nbsp; {{ trans('general.share') }}
                                    </a>
                                @stack('button_share_end')
                            @endif

                            @stack('button_group_start')
                                <div class="dropup header-drop-top">
                                    <button type="button" class="btn btn-primary header-button-top" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                                    <div class="dropdown-menu" role="menu">
                                        @if ($invoice->status != 'cancelled')
                                            @stack('button_pay_start')
                                                @if ($invoice->status != 'paid')
                                                    @permission('update-sales-invoices')
                                                        <a class="dropdown-item" href="{{ route('invoices.paid', $invoice->id) }}">{{ trans('invoices.mark_paid') }}</a>
                                                    @endpermission

                                                    @if(empty($invoice->paid) || ($invoice->paid != $invoice->amount))
                                                        <button class="dropdown-item" id="button-payment" @click="onPayment">{{ trans('invoices.add_payment') }}</button>
                                                    @endif
                                                    <div class="dropdown-divider"></div>
                                                @endif
                                            @stack('button_pay_end')

                                            @stack('button_sent_start')
                                                @permission('update-sales-invoices')
                                                    @if ($invoice->status == 'draft')
                                                        <a class="dropdown-item" href="{{ route('invoices.sent', $invoice->id) }}">{{ trans('invoices.mark_sent') }}</a>
                                                    @else
                                                        <button type="button" class="dropdown-item" disabled="disabled"><span class="text-disabled">{{ trans('invoices.mark_sent') }}</span></button>
                                                    @endif
                                                @endpermission
                                            @stack('button_sent_end')

                                            @stack('button_email_start')
                                                @if ($invoice->contact_email)
                                                    <a class="dropdown-item" href="{{ route('invoices.email', $invoice->id) }}">{{ trans('invoices.send_mail') }}</a>
                                                @else
                                                    <button type="button" class="dropdown-item" disabled="disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}">
                                                        <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                                                    </button>
                                                @endif
                                            @stack('button_email_end')
                                        @endif

                                        @stack('button_pdf_start')
                                            <a class="dropdown-item" href="{{ route('invoices.pdf', $invoice->id) }}">{{ trans('invoices.download_pdf') }}</a>
                                        @stack('button_pdf_end')

                                        @permission('update-sales-invoices')
                                            @if ($invoice->status != 'cancelled')
                                                @stack('button_cancelled_start')
                                                <a class="dropdown-item" href="{{ route('invoices.cancelled', $invoice->id) }}">{{ trans('general.cancel') }}</a>
                                                @stack('button_cancelled_end')
                                            @endif
                                        @endpermission

                                        @permission('delete-sales-invoices')
                                            @if (!$invoice->reconciled)
                                                @stack('button_delete_start')
                                                {!! Form::deleteLink($invoice, 'sales/invoices') !!}
                                                @stack('button_delete_end')
                                            @endif
                                        @endpermission
                                    </div>
                                </div>
                            @stack('button_group_end')
                        </div>
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
                            <div class="card-header" id="accordion-histories-header" data-toggle="collapse" data-target="#accordion-histories-body" aria-expanded="false" aria-controls="accordion-histories-body">
                                <h4 class="mb-0">{{ trans('invoices.histories') }}</h4>
                            </div>
                            <div id="accordion-histories-body" class="collapse hide" aria-labelledby="accordion-histories-header">
                                <div class="table-responsive">
                                    <table class="table table-flush table-hover">
                                        <thead class="thead-light">
                                            <tr class="row table-head-line">
                                                <th class="col-xs-4 col-sm-2">{{ trans('general.date') }}</th>
                                                <th class="col-xs-4 col-sm-3 text-left">{{ trans_choice('general.statuses', 1) }}</th>
                                                <th class="col-xs-4 col-sm-7 text-left long-texts">{{ trans('general.description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->histories as $history)
                                                <tr class="row align-items-center border-top-1 tr-py">
                                                    <td class="col-xs-4 col-sm-2">@date($history->created_at)</td>
                                                    <td class="col-xs-4 col-sm-3 text-left">{{ trans('invoices.statuses.' . $history->status) }}</td>
                                                    <td class="col-xs-4 col-sm-7 text-left long-texts">{{ $history->description }}</td>
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

            @stack('row_footer_transaction_start')
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
                                            <tr class="row table-head-line">
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.date') }}</th>
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.amount') }}</th>
                                                <th class="col-sm-3 d-none d-sm-block">{{ trans_choice('general.accounts', 1) }}</th>
                                                <th class="col-xs-4 col-sm-3">{{ trans('general.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($invoice->transactions->count())
                                                @foreach($invoice->transactions as $transaction)
                                                    <tr class="row align-items-center border-top-1 tr-py">
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @stack('row_footer_transaction_end')
        </div>
    @stack('row_footer_end')
@endsection

@push('content_content_end')
    <akaunting-modal
        class="modal-payment"
        :show="payment.modal"
        @cancel="payment.modal = false"
        :title="'{{ trans('general.title.new', ['type' => trans_choice('general.payments', 1)]) }}'"
        :message="payment.html"
        :button_cancel="'{{ trans('general.button.save') }}'"
        :button_delete="'{{ trans('general.button.cancel') }}'">
        <template #modal-body>
            @include('modals.invoices.payment')
        </template>

        <template #card-footer>
            <div class="float-right">
                <button type="button" class="btn btn-outline-secondary header-button-top" @click="closePayment">
                    {{ trans('general.cancel') }}
                </button>

                <a href="{{ route('apps.categories.show', 'payment-method') }}" class="btn btn-white header-button-top long-texts">
                    {{ trans('invoices.accept_payments') }}</span>
                </a>

                <button :disabled="form.loading" type="button" class="btn btn-success button-submit header-button-top" @click="addPayment">
                    <div class="aka-loader"></div><span>{{ trans('general.confirm') }}</span>
                </button>
            </div>
        </template>
    </akaunting-modal>
@endpush

@push('scripts_start')
    <script src="{{ asset('public/js/sales/invoices.js?v=' . version('short')) }}"></script>
@endpush
