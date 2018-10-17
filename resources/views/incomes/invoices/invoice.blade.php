@extends('layouts.invoice')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
<div class="row header">
    <div class="col-58">
        @if ($logo)
        <img src="{{ $logo }}" class="logo" />
        @endif
    </div>
    <div class="col-42">
        <div class="text company">
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
        </div>
    </div>
</div>

<div class="row">
    <div class="col-58">
        <div class="text">
            {{ trans('invoices.bill_to') }}<br><br>
            <strong>{{ $invoice->customer_name }}</strong><br>
            {!! nl2br($invoice->customer_address) !!}<br>
            @if ($invoice->customer_tax_number)
                {{ trans('general.tax_number') }}: {{ $invoice->customer_tax_number }}<br>
            @endif
            <br>
            @if ($invoice->customer_phone)
                {{ $invoice->customer_phone }}<br>
            @endif
            {{ $invoice->customer_email }}
        </div>
    </div>
    <div class="col-42">
        <div class="text">
            <table>
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

<table class="lines">
    <thead>
        <tr>
            <th class="item">{{ trans_choice($text_override['items'], 2) }}</th>
            <th class="quantity">{{ trans($text_override['quantity']) }}</th>
            <th class="price">{{ trans($text_override['price']) }}</th>
            <th class="total">{{ trans('invoices.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td class="item">
                {{ $item->name }}
                @if ($item->sku)
                    <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                @endif
            </td>
            <td class="quantity">{{ $item->quantity }}</td>
            <td class="price">@money($item->price, $invoice->currency_code, true)</td>
            <td class="total">@money($item->total, $invoice->currency_code, true)</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-58">
        @if ($invoice->notes)
        <table class="text" style="page-break-inside: avoid;">
            <tr><th>{{ trans_choice('general.notes', 2) }}</th></tr>
            <tr><td>{{ $invoice->notes }}</td></tr>
        </table>
        @endif
    </div>
    <div class="col-42">
        <table class="text" style="page-break-inside: avoid;">
            <tbody>
            @foreach ($invoice->totals as $total)
                @if ($total->code != 'total')
                    <tr>
                        <th>{{ trans($total->title) }}:</th>
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
                        <th>{{ trans($total->name) }}:</th>
                        <td class="text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
