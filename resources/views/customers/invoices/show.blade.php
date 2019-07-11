@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
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
                <div class="col-md-10">
                    @stack('button_print_start')
                    <a href="{{ url('customers/invoices/' . $invoice->id . '/print') }}" target="_blank" class="btn btn-default">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    @stack('button_print_end')

                    @stack('button_pdf_start')
                    <a href="{{ url('customers/invoices/' . $invoice->id . '/pdf') }}" class="btn btn-default" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                        <i class="fa fa-file-pdf-o"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                    @stack('button_pdf_end')
                </div>

                <div class="col-md-2 no-padding-right">
                    @if($invoice->invoice_status_code != 'paid')
                        @if ($payment_methods)
                            {!! Form::select('payment_method', $payment_methods, null, array_merge(['id' => 'payment-method', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])])) !!}
                            {!! Form::hidden('invoice_id', $invoice->id, []) !!}
                        @else

                        @endif
                    @endif
                </div>
                <div id="confirm" class="col-md-12"></div>
            </div>
            @stack('box_footer_end')
        </section>
    </div>
    @stack('invoice_end')
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('change', '#payment-method', function (e) {
                var payment_method = $(this).val();

                gateway = payment_method.split('.');

                $.ajax({
                    url: '{{ url("customers/invoices/" . $invoice->id) }}/' + gateway[0],
                    type: 'GET',
                    dataType: 'JSON',
                    data: $('.box-footer input, .box-footer select'),
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('#confirm').html('');

                        $('#confirm').append('<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>');
                    },
                    complete: function() {
                        $('#loading').remove();
                    },
                    success: function(data) {
                        if (data['error']) {

                        }

                        if (data['redirect']) {
                            location = data['redirect'];
                        }

                        if (data['html']) {
                            $('#confirm').append(data['html']);
                        }
                    },
                    error: function(data){

                    }
                });
            });
        });
    </script>
@endpush
