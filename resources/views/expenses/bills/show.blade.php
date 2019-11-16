@extends('layouts.admin')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    @stack('recurring_message_start')
    @if (($recurring = $bill->recurring) && ($next = $recurring->next()))
    <div class="callout callout-info">
        @stack('recurring_message_head_start')
        <h4>{{ trans('recurring.recurring') }}</h4>
        @stack('recurring_message_head_end')

        @stack('recurring_message_body_start')
        <p>{{ trans('recurring.message', [
                'type' => mb_strtolower(trans_choice('general.bills', 1)),
                'date' => $next->format($date_format)
            ]) }}
        </p>
        @stack('recurring_message_body_end')
    </div>
    @endif
    @stack('recurring_message_end')

    @stack('status_message_start')
    @if ($bill->status->code == 'draft')
    <div class="callout callout-warning">
        @stack('status_message_body_start')
        <p>{!! trans('invoices.messages.draft') !!}</p>
        @stack('status_message_body_end')
    </div>
    @endif
    @stack('status_message_end')

    @stack('timeline_start')
    @if ($bill->status->code != 'paid')
    <div class="row show-invoice">
        @stack('timeline_body_start')
        <div class="col-md-12 no-padding-right">
            <ul class="timeline">
                @stack('timeline_body_create_bill_start')
                <li id="timeline-create-bill">
                    <i class="fa fa-plus bg-blue"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_create_bill_head_start')
                        <h3 class="timeline-header">{{ trans('bills.create_bill') }}</h3>
                        @stack('timeline_body_create_bill_head_end')

                        @stack('timeline_body_create_bill_body_start')
                        <div class="timeline-body">
                            @stack('timeline_body_create_bill_body_message_start')
                            {{ trans_choice('general.statuses', 1) . ': ' . trans('bills.messages.status.created', ['date' => Date::parse($bill->created_at)->format($date_format)]) }}
                            @stack('timeline_body_create_bill_body_message_end')

                            @stack('timeline_body_create_bill_body_button_edit_start')
                            <a href="{{ url('expenses/bills/' . $bill->id . '/edit') }}" class="btn btn-default btn-xs">
                                {{ trans('general.edit') }}
                            </a>
                            @stack('timeline_body_create_bill_body_button_edit_end')
                        </div>
                        @stack('timeline_body_create_bill_body_end')
                    </div>
                </li>
                @stack('timeline_body_create_bill_end')

                @stack('timeline_body_receive_bill_start')
                <li id="timeline-receive-bill">
                    <i class="fa fa-envelope bg-orange"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_receive_bill_head_start')
                        <h3 class="timeline-header">{{ trans('bills.receive_bill') }}</h3>
                        @stack('timeline_body_receive_bill_head_end')

                        @stack('timeline_body_receive_bill_body_start')
                        <div class="timeline-body">
                            @if ($bill->status->code == 'draft')
                                @stack('timeline_body_receive_bill_body_message_start')
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('bills.messages.status.receive.draft') }}
                                @stack('timeline_body_receive_bill_body_message_end')

                                @stack('timeline_body_receive_bill_body_button_sent_start')
                                @permission('update-expenses-bills')
                                    <a href="{{ url('expenses/bills/' . $bill->id . '/received') }}" class="btn btn-warning btn-xs">{{ trans('bills.mark_received') }}</a>
                                @endpermission
                                @stack('timeline_body_receive_bill_body_button_sent_end')
                            @else
                                @stack('timeline_body_receive_bill_body_message_start')
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('bills.messages.status.receive.received', ['date' => Date::parse($bill->created_at)->format($date_format)]) }}
                                @stack('timeline_body_receive_bill_body_message_end')
                            @endif
                        </div>
                        @stack('timeline_body_receive_bill_body_end')
                    </div>
                </li>
                @stack('timeline_body_receive_bill_end')

                @stack('timeline_body_make_payment_start')
                <li id="timeline-make-payment">
                    <i class="fa fa-money bg-green"></i>

                    <div class="timeline-item">
                        @stack('timeline_body_make_payment_head_start')
                        <h3 class="timeline-header">{{ trans('bills.make_payment') }}</h3>
                        @stack('timeline_body_make_payment_head_end')

                        @stack('timeline_body_make_payment_body_start')
                        <div class="timeline-body">
                            @stack('timeline_body_get_paid_body_message_start')
                            @if($bill->status->code != 'paid' && empty($bill->payments()->count()))
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('bills.messages.status.paid.await') }}
                            @else
                                {{ trans_choice('general.statuses', 1) . ': ' . trans('general.partially_paid') }}
                            @endif
                            @stack('timeline_body_make_payment_body_message_end')


                            @stack('timeline_body_make_payment_body_button_payment_start')
                            @if(empty($bill->payments()->count()) || (!empty($bill->payments()->count()) && $bill->paid != $bill->amount))
                                <a href="#" id="button-payment" class="btn btn-success btn-xs">{{ trans('bills.add_payment') }}</a>
                            @endif
                            @stack('timeline_body_make_payment_body_button_payment_end')
                        </div>
                        @stack('timeline_body_make_payment_body_end')
                    </div>
                </li>
                @stack('timeline_body_make_payment_end')
            </ul>
        </div>
        @stack('timeline_body_end')
    </div>
    @endif
    @stack('timeline_end')

    @stack('bill_start')
    <div class="box box-success">
        <section class="bill">
            @stack('bill_badge_start')
            <div id="badge">
                <div class="arrow-up"></div>
                <div class="label {{ $bill->status->label }}">{{ trans('bills.status.' . $bill->status->code) }}</div>
                <div class="arrow-right"></div>
            </div>
            @stack('bill_badge_end')

            @stack('bill_header_start')
            <div class="row invoice-header">
                <div class="col-xs-7">
                    @if (isset($bill->vendor->logo) && !empty($bill->vendor->logo->id))
                        <img src="{{ Storage::url($bill->vendor->logo->id) }}" class="invoice-logo" />
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
            @stack('bill_header_end')

            @stack('bill_information_start')
            <div class="row">
                <div class="col-xs-7">
                    {{ trans('bills.bill_from') }}
                    <address>
                        @stack('name_input_start')
                        <strong>{{ $bill->vendor_name }}</strong><br>
                        @stack('name_input_end')

                        @stack('address_input_start')
                        {!! nl2br($bill->vendor_address) !!}<br>
                        @stack('address_input_end')

                        @stack('tax_number_input_start')
                        @if ($bill->vendor_tax_number)
                        {{ trans('general.tax_number') }}: {{ $bill->vendor_tax_number }}<br>
                        @endif
                        @stack('tax_number_input_end')
                        <br>
                        @stack('phone_input_start')
                        @if ($bill->vendor_phone)
                        {{ $bill->vendor_phone }}<br>
                        @endif
                        @stack('phone_input_end')

                        @stack('email_start')
                        {{ $bill->vendor_email }}
                        @stack('email_input_end')
                    </address>
                </div>

                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table no-border">
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
                                    <td class="text-right">{{ Date::parse($bill->billed_at)->format($date_format) }}</td>
                                </tr>
                                @stack('billed_at_input_end')

                                @stack('due_at_input_start')
                                <tr>
                                    <th>{{ trans('bills.payment_due') }}:</th>
                                    <td class="text-right">{{ Date::parse($bill->due_at)->format($date_format) }}</td>
                                </tr>
                                @stack('due_at_input_end')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @stack('bill_information_end')

            @stack('bill_item_start')
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                @stack('actions_th_start')
                                @stack('actions_th_end')

                                @stack('name_th_start')
                                <th>{{ trans_choice('general.items', 1) }}</th>
                                @stack('name_th_end')

                                @stack('quantity_th_start')
                                <th class="text-center">{{ trans('bills.quantity') }}</th>
                                @stack('quantity_th_end')

                                @stack('price_th_start')
                                <th class="text-right">{{ trans('bills.price') }}</th>
                                @stack('price_th_end')

                                @stack('taxes_th_start')
                                @stack('taxes_th_end')

                                @stack('total_th_start')
                                <th class="text-right">{{ trans('bills.total') }}</th>
                                @stack('total_th_end')
                            </tr>
                            @foreach($bill->items as $item)
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
                                <td class="text-right">@money($item->price, $bill->currency_code, true)</td>
                                @stack('price_td_end')

                                @stack('taxes_td_start')
                                @stack('taxes_td_end')

                                @stack('total_td_start')
                                <td class="text-right">@money($item->total, $bill->currency_code, true)</td>
                                @stack('total_td_end')
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @stack('bill_item_end')

            @stack('bill_total_start')
            <div class="row">
                <div class="col-xs-7">
                @stack('notes_input_start')
                @if ($bill->notes)
                    <p class="lead">{{ trans_choice('general.notes', 2) }}:</p>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ $bill->notes }}
                    </p>
                @endif
                @stack('notes_input_end')
                </div>

                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach ($bill->totals as $total)
                                @if ($total->code != 'total')
                                    @stack($total->code . '_td_start')
                                    <tr>
                                        <th>{{ trans($total->title) }}:</th>
                                        <td class="text-right">@money($total->amount, $bill->currency_code, true)</td>
                                    </tr>
                                    @stack($total->code . '_td_end')
                                @else
                                    @if ($bill->paid)
                                        <tr class="text-success">
                                            <th>{{ trans('invoices.paid') }}:</th>
                                            <td class="text-right">- @money($bill->paid, $bill->currency_code, true)</td>
                                        </tr>
                                    @endif
                                    @stack('grand_total_td_start')
                                    <tr>
                                        <th>{{ trans($total->name) }}:</th>
                                        <td class="text-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                                    </tr>
                                    @stack('grand_total_td_end')
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @stack('bill_total_end')

            @stack('box_footer_start')
            <div class="box-footer row no-print">
                <div class="col-md-12">
                    @stack('button_edit_start')
                    @if(!$bill->reconciled)
                    <a href="{{ url('expenses/bills/' . $bill->id . '/edit') }}" class="btn btn-default">
                        <i class="fa fa-pencil-square-o"></i>&nbsp; {{ trans('general.edit') }}
                    </a>
                    @endif
                    @stack('button_edit_end')
                    @stack('button_print_start')
                    <a href="{{ url('expenses/bills/' . $bill->id . '/print') }}" target="_blank" class="btn btn-success">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    @stack('button_print_end')
                    @stack('button_group_start')
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-circle-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                        <ul class="dropdown-menu" role="menu">
                            @stack('button_pay_start')
                            @if($bill->status->code != 'paid')
                            @if(empty($bill->paid) || ($bill->paid != $bill->amount))
                            <li><a href="#" id="button-payment">{{ trans('bills.add_payment') }}</a></li>
                            @endif

                            @permission('update-expenses-bills')
                            @if($bill->bill_status_code == 'draft')
                            <li><a href="{{ url('expenses/bills/' . $bill->id . '/received') }}">{{ trans('bills.mark_received') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="disabled"><span class="text-disabled">{{ trans('bills.mark_received') }}</span></a></li>
                            @endif
                            @endpermission

                            <li class="divider"></li>
                            @endif
                            @stack('button_pay_end')

                            @stack('button_pdf_start')
                            <li><a href="{{ url('expenses/bills/' . $bill->id . '/pdf') }}">{{ trans('bills.download_pdf') }}</a></li>
                            @stack('button_pdf_end')

                            @stack('button_delete_start')
                            @permission('delete-expenses-bills')
                            @if(!$bill->reconciled)
                            <li class="divider"></li>

                            <li>{!! Form::deleteLink($bill, 'expenses/bills') !!}</li>
                            @endif
                            @endpermission
                            @stack('button_delete_end')
                        </ul>
                    </div>
                    @stack('button_group_end')

                    @if($bill->attachment)
                    @php $file = $bill->attachment; @endphp
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
                    <h3 class="box-title">{{ trans('bills.histories') }}</h3>

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
                            @foreach($bill->histories as $history)
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
                    <h3 class="box-title">{{ trans('bills.payments') }}</h3>

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
                            @foreach($bill->payments as $payment)
                                <tr>
                                    <td>{{ Date::parse($payment->paid_at)->format($date_format) }}</td>
                                    <td>@money($payment->amount, $payment->currency_code, true)</td>
                                    <td>{{ $payment->account->name }}</td>
                                    <td>
                                        @if ($payment->reconciled)
                                        <button type="button" class="btn btn-default btn-xs" data-toggle="tooltip" data-placement="top" title="{{ trans('reconciliations.reconciled') }}">
                                            <i class="fa fa-check"></i>
                                        </button>
                                        @else
                                        <a href="{{ url('expenses/bills/' . $payment->id) }}" class="btn btn-info btn-xs hidden"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                        <a href="{{ url('expenses/bills/' . $payment->id . '/edit') }}" class="btn btn-primary btn-xs  hidden"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                                        {!! Form::open([
                                            'id' => 'bill-payment-' . $payment->id,
                                            'method' => 'DELETE',
                                            'url' => ['expenses/bills/payment', $payment->id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'btn btn-danger btn-xs',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmDelete("' . '#bill-payment-' . $payment->id . '", "' . trans_choice('general.payments', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . Date::parse($payment->paid_at)->format($date_format) . ' - ' . money($payment->amount, $payment->currency_code, true) . ' - ' . $payment->account->name . '</strong>', 'type' => strtolower(trans_choice('general.revenues', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
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
        @if($bill->attachment)
        $(document).on('click', '#remove-attachment', function (e) {
            confirmDelete("#attachment-{!! $bill->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $bill->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
        });
        @endif
        @endpermission

        $(document).on('click', '#button-payment', function (e) {
            $('#modal-add-payment').remove();

            $.ajax({
                url: '{{ url("modals/bills/" . $bill->id . "/payment/create") }}',
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
