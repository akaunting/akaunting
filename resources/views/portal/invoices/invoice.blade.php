@extends('layouts.portal')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    <div class="row">
        <div class="col-md-7">
            @if ($logo)
                <img src="{{ $logo }}"/>
            @endif
        </div>
        <div class="col-md-5 invoice-company">
            <address>
                <strong>{{ setting('company.name') }}</strong><br>
                {{ setting('company.address') }}<br>
                @if (setting('company.tax_number'))
                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}<br>
                @endif
                <br>
                @if (setting('company.phone'))
                    {{ setting('company.phone') }}<br>
                @endif
                {{ setting('company.email') }}
            </address>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            {{ trans('invoices.bill_to') }}
            <address>
                <strong>{{ $invoice->contact_name }}</strong><br>
                {{ $invoice->contact_address }}<br>
                @if ($invoice->contact_tax_number)
                    {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}<br>
                @endif
                <br>
                @if ($invoice->contact_phone)
                    {{ $invoice->contact_phone }}<br>
                @endif
                {{ $invoice->contact_email }}
            </address>
        </div>
        <div class="col-md-5">
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
                            <td class="text-right">@date($invoice->invoiced_at)</td>
                        </tr>
                        <tr>
                            <th>{{ trans('invoices.payment_due') }}:</th>
                            <td class="text-right">@date($invoice->due_At)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 table-responsive">
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
        <div class="col-md-7">
            @if ($invoice->notes)
                <p class="lead">{{ trans_choice('general.notes', 2) }}</p>

                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                    {{ $invoice->notes }}
                </p>
            @endif
        </div>
        <div class="col-md-5">
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
@endsection
