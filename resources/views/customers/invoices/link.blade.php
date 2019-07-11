@extends('layouts.link')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('new_button')
    <span class="new-button"><a href="{{ url('customers') }}" class="btn btn-default btn-sm"><span class="fa fa-user-o"></span> &nbsp;{{ trans('invoices.all_invoices') }}</a></span>
@endsection

@section('content')
    <div class="box box-success">
        <section class="invoice">
            <div id="badge">
                <div class="arrow-up"></div>
                <div class="label {{ $invoice->status->label }}">{{ trans('invoices.status.' . $invoice->status->code) }}</div>
                <div class="arrow-right"></div>
            </div>

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
                        {{ setting('general.company_address') }}<br>
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

            <div class="row">
                <div class="col-xs-7">
                    {{ trans('invoices.bill_to') }}
                    <address>
                        <strong>{{ $invoice->customer_name }}</strong><br>
                        {{ $invoice->customer_address }}<br>
                        @if ($invoice->customer_tax_number)
                            {{ trans('general.tax_number') }}: {{ $invoice->customer_tax_number }}<br>
                        @endif
                        <br>
                        @if ($invoice->customer_phone)
                            {{ $invoice->customer_phone }}<br>
                        @endif
                        {{ $invoice->customer_email }}
                    </address>
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table no-border">
                            <tbody>
                            <tr>
                                <th>{{ trans('invoices.invoice_number') }}:</th>
                                <td class="text-right">{{ $invoice->invoice_number }}</td>
                            </tr>
                            @if ($invoice->order_number)
                                <tr>
                                    <th>{{ trans('invoices.order_number') }}:</th>
                                    <td class="text-right">{{ $invoice->order_number }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>{{ trans('invoices.invoice_date') }}:</th>
                                <td class="text-right">{{ Date::parse($invoice->invoiced_at)->format($date_format) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('invoices.payment_due') }}:</th>
                                <td class="text-right">{{ Date::parse($invoice->due_at)->format($date_format) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>{{ trans_choice('general.items', 1) }}</th>
                            <th class="text-center">{{ trans('invoices.quantity') }}</th>
                            <th class="text-right">{{ trans('invoices.price') }}</th>
                            <th class="text-right">{{ trans('invoices.total') }}</th>
                        </tr>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>
                                    {{ $item->name }}
                                    @if ($item->sku)
                                        <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">@money($item->price, $invoice->currency_code, true)</td>
                                <td class="text-right">@money($item->total, $invoice->currency_code, true)</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-7">
                    @if ($invoice->notes)
                        <p class="lead">{{ trans_choice('general.notes', 2) }}</p>

                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            {{ $invoice->notes }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            @foreach($invoice->totals as $total)
                                @if($total->code != 'total')
                                    <tr>
                                        <th>{{ trans($total['name']) }}:</th>
                                        <td class="text-right">@money($total->amount, $invoice->currency_code, true)</td>
                                    </tr>
                                @else
                                    @if ($invoice->paid)
                                        <tr class="text-success">
                                            <th>{{ trans('invoices.paid') }}:</th>
                                            <td class="text-right">- @money($invoice->paid, $invoice->currency_code, true)</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans($total['name']) }}:</th>
                                        <td class="text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box-footer row no-print">
                <div class="col-md-10">
                    <a href="{{ $print_action }}" target="_blank" class="btn btn-default">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <a href="{{ $pdf_action }}" class="btn btn-default" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                        <i class="fa fa-file-pdf-o"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                </div>

                <div class="col-md-2 no-padding-right">
                    @if($invoice->invoice_status_code != 'paid')
                        @if ($payment_methods)
                            {!! Form::select('payment_method', $payment_methods, null, array_merge(['id' => 'payment-method', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])])) !!}
                            {!! Form::select('payment_method_actions', $payment_actions, null, array_merge(['id' => 'payment-method-actions', 'class' => 'form-control hidden'])) !!}
                            {!! Form::hidden('invoice_id', $invoice->id, []) !!}
                        @else

                        @endif
                    @endif
                </div>
                <div id="confirm" class="col-md-12"></div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('change', '#payment-method', function (e) {
                var payment_method = $(this).val();

                gateway = payment_method.split('.');

                $('#payment-method-actions').val(gateway[0]);

                var url = $('#payment-method-actions').find('option:selected').text();

                $.ajax({
                    url: url,
                    type: 'POST',
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
