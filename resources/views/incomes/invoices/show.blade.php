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
    <div class="callout callout-warning">
        @stack('status_message_body_start')
        <p>{!! trans('invoices.messages.draft') !!}</p>
        @stack('status_message_body_end')
    </div>
    @endif
    @stack('status_message_end')

    @stack('timeline_start')
    @if ($invoice->status->code != 'paid')
    <div class="row show-invoice">
        @stack('timeline_body_start')
        <div class="col-md-12 no-padding-right">
            <ul class="timeline">
                @stack('timeline_body_create_invoice_start')
                <li id="timeline-create-invoice">
                    <i class="fa fa-plus bg-blue"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_create_invoice_head_start')
                        <h3 class="timeline-header">{{ trans('invoices.create_invoice') }}</h3>
                        @stack('timeline_body_create_invoice_head_end')

                        @stack('timeline_body_create_invoice_body_start')
                        <div class="timeline-body">
                            @stack('timeline_body_create_invoice_body_message_start')
                            {{ trans_choice('general.statuses', 1) . ': ' . trans('invoices.messages.status.created', ['date' => Date::parse($invoice->created_at)->format($date_format)]) }}
                            @stack('timeline_body_create_invoice_body_message_end')

                            @stack('timeline_body_create_invoice_body_button_edit_start')
                            <a href="{{ url('incomes/invoices/' . $invoice->id . '/edit') }}" class="btn btn-default btn-xs">
                                {{ trans('general.edit') }}
                            </a>
                            @stack('timeline_body_create_invoice_body_button_edit_end')
                        </div>
                        @stack('timeline_body_create_invoice_body_end')
                    </div>
                </li>
                @stack('timeline_body_create_invoice_end')

                @stack('timeline_body_send_invoice_start')
                <li id="timeline-send-invoice">
                    <i class="fa fa-envelope bg-orange"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_send_invoice_head_start')
                        <h3 class="timeline-header">{{ trans('invoices.send_invoice') }}</h3>
                        @stack('timeline_body_send_invoice_head_end')

                        @stack('timeline_body_send_invoice_body_start')
                        <div class="timeline-body">
                            @if ($invoice->status->code != 'sent' && $invoice->status->code != 'partial')
                                @stack('timeline_body_send_invoice_body_message_start')
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('invoices.messages.status.send.draft') }}
                                @stack('timeline_body_send_invoice_body_message_end')

                                @stack('timeline_body_send_invoice_body_button_sent_start')
                                @permission('update-incomes-invoices')
                                @if($invoice->invoice_status_code == 'draft')
                                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/sent') }}" class="btn btn-default btn-xs">{{ trans('invoices.mark_sent') }}</a>
                                @else
                                    <a href="javascript:void(0);" class="disabled btn btn-default btn-xs"><span class="text-disabled"> {{ trans('invoices.mark_sent') }}</span></a>
                                @endif
                                @endpermission
                                @stack('timeline_body_send_invoice_body_button_sent_end')

                                @stack('timeline_body_send_invoice_body_button_email_start')
                                @if($invoice->customer_email)
                                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/email') }}" class="btn btn-warning btn-xs">{{ trans('invoices.send_mail') }}</a>
                                @else
                                    <a href="javascript:void(0);" class="btn btn-warning btn-xs green-tooltip disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}">
                                        <span class="text-disabled">{{ trans('invoices.send_mail') }}</span>
                                    </a>
                                @endif
                                @stack('timeline_body_send_invoice_body_button_email_end')
                            @else
                                @stack('timeline_body_send_invoice_body_message_start')
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('invoices.messages.status.send.sent', ['date' => Date::parse($invoice->created_at)->format($date_format)]) }}
                                @stack('timeline_body_send_invoice_body_message_end')
                            @endif
                        </div>
                        @stack('timeline_body_send_invoice_body_end')
                    </div>
                </li>
                @stack('timeline_body_send_invoice_end')

                @stack('timeline_body_get_paid_start')
                <li id="timeline-get-paid">
                    <i class="fa fa-money bg-green"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_get_paid_head_start')
                        <h3 class="timeline-header">{{ trans('invoices.get_paid') }}</h3>
                        @stack('timeline_body_get_paid_head_end')

                        @stack('timeline_body_get_paid_body_start')
                        <div class="timeline-body">
                            @stack('timeline_body_get_paid_body_message_start')
                            @if($invoice->status->code != 'paid' && empty($invoice->payments()->count()))
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('invoices.messages.status.paid.await') }}
                            @else
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('general.partially_paid') }}
                            @endif
                            @stack('timeline_body_get_paid_body_message_end')

                            @stack('timeline_body_get_paid_body_button_pay_start')
                            @permission('update-incomes-invoices')
                            <a href="{{ url('incomes/invoices/' . $invoice->id . '/pay') }}" class="btn btn-default btn-xs">{{ trans('invoices.mark_paid') }}</a>
                            @endpermission
                            @stack('timeline_body_get_paid_body_button_pay_end')

                            @stack('timeline_body_get_paid_body_button_payment_start')
                            @if(empty($invoice->payments()->count()) || (!empty($invoice->payments()->count()) && $invoice->paid != $invoice->amount))
                                <a href="#" id="button-payment" class="btn btn-success btn-xs">{{ trans('invoices.add_payment') }}</a>
                            @endif
                            @stack('timeline_body_get_paid_body_button_payment_end')
                        </div>
                        @stack('timeline_body_get_paid_body_end')
                    </div>
                </li>
                @stack('timeline_body_get_paid_end')
            </ul>
        </div>
        @stack('timeline_body_end')
    </div>
    @endif
    @stack('timeline_end')

    @stack('invoice_start')
    <div class="box box-success">
        <section class="invoice">
            @stack('invoice_badge_start')
            <div id="badge">
                <div class="arrow-up"></div>
                <div class="label {{ $invoice->status->label }}">{{ trans('invoices.status.' . $invoice->status->code) }}</div>
                <div class="arrow-right"></div>
            </div>
            @stack('invoice_badge_end')

            @stack('invoice_header_start')
            <div class="row invoice-header">
                <div class="col-xs-7">
                    @if (setting('general.invoice_logo'))
                        <img src="{{ Storage::url(setting('general.invoice_logo')) }}" class="invoice-logo" />
                    @elseif (setting('general.company_logo'))
                        <img src="{{ Storage::url(setting('general.company_logo')) }}" class="invoice-logo" />
                    @else
                        <img src="{{ asset('public/img/company.png') }}" class="invoice-logo" />
                    @endif
                </div>

                <div class="col-xs-5 invoice-company">
                    <address>
                        <strong>{{ setting('general.company_name') }}</strong><br>
                        {!! nl2br(setting('general.company_address')) !!}<br>
                        @if (setting('general.company_tax_number'))
                        {{ trans('general.tax_number') }}: {{ setting('general.company_tax_number') }}<br>
                        @endif
                        <br>
                        @if (setting('general.company_phone'))
                        {{ setting('general.company_phone') }}<br>
                        @endif
                        {{ setting('general.company_email') }}
                    </address>
                </div>
            </div>
            @stack('invoice_header_end')

            @stack('invoice_information_start')
            <div class="row">
                <div class="col-xs-7">
                    {{ trans('invoices.bill_to') }}
                    <address>
                        @stack('name_input_start')
                        <strong>{{ $invoice->customer_name }}</strong><br>
                        @stack('name_input_end')

                        @stack('address_input_start')
                        {!! nl2br($invoice->customer_address) !!}<br>
                        @stack('address_input_end')

                        @stack('tax_number_input_start')
                        @if ($invoice->customer_tax_number)
                        {{ trans('general.tax_number') }}: {{ $invoice->customer_tax_number }}<br>
                        @endif
                        @stack('tax_number_input_end')
                        <br>
                        @stack('phone_input_start')
                        @if ($invoice->customer_phone)
                        {{ $invoice->customer_phone }}<br>
                        @endif
                        @stack('phone_input_end')

                        @stack('email_start')
                        {{ $invoice->customer_email }}
                        @stack('email_input_end')
                    </address>
                </div>

                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table no-border">
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
                                    <td class="text-right">{{ Date::parse($invoice->invoiced_at)->format($date_format) }}</td>
                                </tr>
                                @stack('invoiced_at_input_end')

                                @stack('due_at_input_start')
                                <tr>
                                    <th>{{ trans('invoices.payment_due') }}:</th>
                                    <td class="text-right">{{ Date::parse($invoice->due_at)->format($date_format) }}</td>
                                </tr>
                                @stack('due_at_input_end')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @stack('invoice_information_end')

            @stack('invoice_item_start')
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                @stack('actions_th_start')
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                <th>{{ trans_choice($text_override['items'], 2) }}</th>
                                @stack('name_th_end')

                                @stack('quantity_th_start')
                                <th class="text-center">{{ trans($text_override['quantity']) }}</th>
                                @stack('quantity_th_end')

                                @stack('price_th_start')
                                <th class="text-right">{{ trans($text_override['price']) }}</th>
                                @stack('price_th_end')

                                @stack('taxes_th_start')
                                @stack('taxes_th_end')

                                @stack('total_th_start')
                                <th class="text-right">{{ trans('invoices.total') }}</th>
                                @stack('total_th_end')
                            </tr>
                            @foreach($invoice->items as $item)
                            <tr>
                                @stack('actions_td_start')
                                @stack('actions_td_end')

                                @stack('name_td_start')
                                <td>
                                    {{ $item->name }}
                                    @if ($item->sku)
                                        <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                                    @endif
                                </td>
                                @stack('name_td_end')

                                @stack('quantity_td_start')
                                <td class="text-center">{{ $item->quantity }}</td>
                                @stack('quantity_td_end')

                                @stack('price_td_start')
                                <td class="text-right">@money($item->price, $invoice->currency_code, true)</td>
                                @stack('price_td_end')

                                @stack('taxes_td_start')
                                @stack('taxes_td_end')

                                @stack('total_td_start')
                                <td class="text-right">@money($item->total, $invoice->currency_code, true)</td>
                                @stack('total_td_end')
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @stack('invoice_item_end')

            @stack('invoice_total_start')
            <div class="row">
                <div class="col-xs-7">
                @stack('notes_input_start')
                @if ($invoice->notes)
                    <p class="lead">{{ trans_choice('general.notes', 2) }}</p>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ $invoice->notes }}
                    </p>
                @endif
                @stack('notes_input_end')
                </div>

                <div class="col-xs-5">
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

            @stack('box_footer_start')
            <div class="box-footer row no-print">
                <div class="col-md-12">
                    @stack('button_edit_start')
                    @if(!$invoice->reconciled)
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/edit') }}" class="btn btn-default">
                        <i class="fa fa-pencil-square-o"></i>&nbsp; {{ trans('general.edit') }}
                    </a>
                    @endif
                    @stack('button_edit_end')

                    @stack('button_print_start')
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/print') }}" target="_blank" class="btn btn-success">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    @stack('button_print_end')

                    @stack('button_share_start')
                    <a href="{{ $customer_share }}" target="_blank" class="btn btn-primary">
                        <i class="fa fa-share"></i>&nbsp; Share
                    </a>
                    @stack('button_share_end')

                    @stack('button_group_start')
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-circle-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                        <ul class="dropdown-menu" role="menu">
                            @stack('button_pay_start')
                            @if($invoice->status->code != 'paid')
                            @permission('update-incomes-invoices')
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/pay') }}">{{ trans('invoices.mark_paid') }}</a></li>
                            @endpermission

                            @if(empty($invoice->paid) || ($invoice->paid != $invoice->amount))
                            <li><a href="#" id="button-payment">{{ trans('invoices.add_payment') }}</a></li>
                            @endif

                            <li class="divider"></li>
                            @endif
                            @stack('button_pay_end')

                            @stack('button_sent_start')
                            @permission('update-incomes-invoices')
                            @if($invoice->invoice_status_code == 'draft')
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/sent') }}">{{ trans('invoices.mark_sent') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="disabled"><span class="text-disabled">{{ trans('invoices.mark_sent') }}</span></a></li>
                            @endif
                            @endpermission
                            @stack('button_sent_end')

                            @stack('button_email_start')
                            @if($invoice->customer_email)
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/email') }}">{{ trans('invoices.send_mail') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="green-tooltip disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}"><span class="text-disabled">{{ trans('invoices.send_mail') }}</span></a></li>
                            @endif
                            @stack('button_email_end')

                            <li class="divider"></li>

                            @stack('button_pdf_start')
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/pdf') }}">{{ trans('invoices.download_pdf') }}</a></li>
                            @stack('button_pdf_end')

                            @stack('button_delete_start')
                            @permission('delete-incomes-invoices')
                            @if(!$invoice->reconciled)
                            <li class="divider"></li>

                            <li>{!! Form::deleteLink($invoice, 'incomes/invoices') !!}</li>
                            @endif
                            @endpermission
                            @stack('button_delete_end')
                        </ul>
                    </div>
                    @stack('button_group_end')

                    @if($invoice->attachment)
                    @php $file = $invoice->attachment; @endphp
                    @include('partials.media.file')
                    @endif
                </div>
            </div>
            @stack('box_footer_end')
        </section>
    </div>
    @stack('invoice_end')

    @stack('row_footer_start')
    <div class="row">
        @stack('row_footer_history_start')
        <div class="col-md-6 col-sm-12">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('invoices.histories') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                    <!-- /.box-tools -->
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('general.date') }}</th>
                                    <th>{{ trans_choice('general.statuses', 1) }}</th>
                                    <th>{{ trans('general.description') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->histories as $history)
                                <tr>
                                    <td>{{ Date::parse($history->created_at)->format($date_format) }}</td>
                                    <td>{{ $history->status->name }}</td>
                                    <td>{{ $history->description }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @stack('row_footer_history_end')

        @stack('row_footer_payment_start')
        <div class="col-md-6 col-sm-12">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('invoices.payments') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                    <!-- /.box-tools -->
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('general.date') }}</th>
                                    <th>{{ trans('general.amount') }}</th>
                                    <th>{{ trans_choice('general.accounts', 1) }}</th>
                                    <th style="width: 15%;">{{ trans('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ Date::parse($payment->paid_at)->format($date_format) }}</td>
                                    <td>@money($payment->amount, $payment->currency_code, true)</td>
                                    <td>{{ $payment->account->name }}</td>
                                    <td>
                                        @if ($payment->reconciled)
                                        <button type="button" class="btn btn-default btn-xs">
                                            <i class="fa fa-check"></i> {{ trans('reconciliations.reconciled') }}
                                        </button>
                                        @else
                                        <a href="{{ url('incomes/invoices/' . $payment->id . '') }}" class="btn btn-info btn-xs hidden"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                        <a href="{{ url('incomes/revenues/' . $payment->id . '/edit') }}" class="btn btn-primary btn-xs  hidden"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                                        {!! Form::open([
                                            'id' => 'invoice-payment-' . $payment->id,
                                            'method' => 'DELETE',
                                            'url' => ['incomes/invoices/payment', $payment->id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'btn btn-danger btn-xs',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmDelete("' . '#invoice-payment-' . $payment->id . '", "' . trans_choice('general.payments', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . Date::parse($payment->paid_at)->format($date_format) . ' - ' . money($payment->amount, $payment->currency_code, true) . ' - ' . $payment->account->name . '</strong>', 'type' => strtolower(trans_choice('general.revenues', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                        )) !!}
                                        {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @stack('row_footer_payment_end')
    </div>
    @stack('row_footer_end')
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @if (language()->getShortCode() != 'en')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    @endif
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        @permission('delete-common-uploads')
        @if($invoice->attachment)
        $(document).on('click', '#remove-attachment', function (e) {
            confirmDelete("#attachment-{!! $invoice->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $invoice->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
        });
        @endif
        @endpermission

        $(document).on('click', '#button-payment', function (e) {
            $('#modal-add-payment').remove();

            $.ajax({
                url: '{{ url("modals/invoices/" . $invoice->id . "/payment/create") }}',
                type: 'GET',
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
        });
    </script>
@endpush
