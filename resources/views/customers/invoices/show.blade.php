@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    <div class="box box-success">
        <section class="invoice">
            <span class="badge bg-aqua">{{ $invoice->status->name }}</span>

            <div class="row invoice-header">
                <div class="col-xs-7">
                    @if (setting('general.invoice_logo'))
                        <img src="{{ asset(setting('general.invoice_logo')) }}" class="invoice-logo" />
                    @else
                        <img src="{{ asset(setting('general.company_logo')) }}" class="invoice-logo" />
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
                                <td class="text-right">@money($item->total - $item->tax, $invoice->currency_code, true)</td>
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
                            <tr>
                                <th>{{ trans('invoices.sub_total') }}:</th>
                                <td class="text-right">@money($invoice->sub_total, $invoice->currency_code, true)</td>
                            </tr>
                            <tr>
                                <th>{{ trans('invoices.tax_total') }}:</th>
                                <td class="text-right">@money($invoice->tax_total, $invoice->currency_code, true)</td>
                            </tr>
                            @if ($invoice->paid)
                                <tr>
                                    <th>{{ trans('invoices.paid') }}:</th>
                                    <td class="text-right">@money('-' . $invoice->paid, $invoice->currency_code, true)</td>
                                </tr>
                            @endif
                            <tr>
                                <th>{{ trans('invoices.total') }}:</th>
                                <td class="text-right">@money($invoice->grand_total, $invoice->currency_code, true)</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box-footer row no-print">
                <div class="col-md-10">
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/print') }}" target="_blank" class="btn btn-default">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/pdf') }}" class="btn btn-default" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                        <i class="fa fa-file-pdf-o"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                </div>

                <div class="col-sm-2">
                    {!! Form::select('payment_method', $payment_methods, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])])) !!}
                    {!! Form::hidden('invoice_id', $invoice->id, []) !!}
                    <button type="button" id="button-payment" class="btn btn-success">
                        <i class="fa fa-credit-card"></i>&nbsp; {{ trans('invoices.add_payment') }}
                    </button>
                </div>
                <div class="confirm"></div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '#button-payment', function (e) {
                $.ajax({
                    url: '{{ url("customers/invoices/payment") }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $('.box-footer input, .box-footer select'),
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    beforeSend: function() {
                        $('.confirm').append('<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>');
                    },
                    complete: function() {
                        $('#loading').remove();
                    },
                    success: function(data) {
                        $("#payment-modal").modal('hide');

                        location.reload();
                    },
                    error: function(data){

                    }
                });
            });
        });
    </script>
@endpush
