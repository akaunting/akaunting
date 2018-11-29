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
        </div>
    </div>
    <div class="col-42">
        <div class="text">
            <table>
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

<table class="lines">
    <thead>
        <tr>
            @stack('actions_th_start')
            @stack('actions_th_end')
            @stack('name_th_start')
            <th class="item">{{ trans_choice($text_override['items'], 2) }}</th>
            @stack('name_th_end')
            @stack('quantity_th_start')
            <th class="quantity">{{ trans($text_override['quantity']) }}</th>
            @stack('quantity_th_end')
            @stack('price_th_start')
            <th class="price">{{ trans($text_override['price']) }}</th>
            @stack('price_th_end')
            @stack('taxes_th_start')
            @stack('taxes_th_end')
            @stack('total_th_start')
            <th class="total">{{ trans('invoices.total') }}</th>
            @stack('total_th_end')
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $item)
        <tr>
            @stack('actions_td_start')
            @stack('actions_td_end')
            @stack('name_td_start')
            <td class="item">
                {{ $item->name }}
                @if ($item->sku)
                    <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                @endif
            </td>
            @stack('name_td_end')
            @stack('quantity_td_start')
            <td class="quantity">{{ $item->quantity }}</td>
            @stack('quantity_td_end')
            @stack('price_td_start')
            <td class="style-price price">@money($item->price, $invoice->currency_code, true)</td>
            @stack('price_td_end')
            @stack('taxes_td_start')
            @stack('taxes_td_end')
            @stack('total_td_start')
            <td class="style-price total">@money($item->total, $invoice->currency_code, true)</td>
            @stack('total_td_end')
        </tr>
        @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-58">
        @stack('notes_input_start')
        @if ($invoice->notes)
        <table class="text" style="page-break-inside: avoid;">
            <tr><th>{{ trans_choice('general.notes', 2) }}</th></tr>
            <tr><td>{{ $invoice->notes }}</td></tr>
        </table>
        @endif
        @stack('notes_input_end')
    </div>
    <div class="col-42">
        <table class="text" style="page-break-inside: avoid;">
            <tbody>
            @foreach ($invoice->totals as $total)
                @if ($total->code != 'total')
                    @stack($total->code . '_td_start')
                    <tr>
                        <th>{{ trans($total->title) }}:</th>
                        <td class="style-price text-right">@money($total->amount, $invoice->currency_code, true)</td>
                    </tr>
                    @stack($total->code . '_td_end')
                @else
                    @if ($invoice->paid)
                        <tr class="text-success">
                            <th>{{ trans('invoices.paid') }}:</th>
                            <td class="style-price text-right">- @money($invoice->paid, $invoice->currency_code, true)</td>
                        </tr>
                    @endif
                    @stack('grand_total_td_start')
                    <tr>
                        <th>{{ trans($total->name) }}:</th>
                        <td class="style-price text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                    </tr>
                    @stack('grand_total_td_end')
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@if (isset($currency_style) && $currency_style)
@push('stylesheet')
<style type="text/css">
    .style-price {
        font-family: sans-serif;
        font-size: 15px;
    }
</style>
@endpush
@endif