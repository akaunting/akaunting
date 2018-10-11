@extends('layouts.bill')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

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
            {{ trans('bills.bill_from') }}<br><br>
            <strong>{{ $bill->vendor_name }}</strong><br>
            {!! nl2br($bill->vendor_address) !!}<br>
            @if ($bill->vendor_tax_number)
                {{ trans('general.tax_number') }}: {{ $bill->vendor_tax_number }}<br>
            @endif
            <br>
            @if ($bill->vendor_phone)
                {{ $bill->vendor_phone }}<br>
            @endif
            {{ $bill->vendor_email }}
        </div>
    </div>
    <div class="col-42">
        <div class="text">
            <table>
                <tbody>
                    <tr>
                        <th>{{ trans('bills.bill_number') }}:</th>
                        <td class="text-right">{{ $bill->bill_number }}</td>
                    </tr>
                    @if ($bill->order_number)
                    <tr>
                        <th>{{ trans('bills.order_number') }}:</th>
                        <td class="text-right">{{ $bill->order_number }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>{{ trans('bills.bill_date') }}:</th>
                        <td class="text-right">{{ Date::parse($bill->billed_at)->format($date_format) }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('bills.payment_due') }}:</th>
                        <td class="text-right">{{ Date::parse($bill->due_at)->format($date_format) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<table class="lines">
    <thead>
        <tr>
            <th class="item">{{ trans_choice('general.items', 1) }}</th>
            <th class="quantity">{{ trans('bills.quantity') }}</th>
            <th class="price">{{ trans('bills.price') }}</th>
            <th class="total">{{ trans('bills.total') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bill->items as $item)
        <tr>
            <td class="item">
                {{ $item->name }}
                @if ($item->sku)
                    <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                @endif
            </td>
            <td class="quantity">{{ $item->quantity }}</td>
            <td class="price">@money($item->price, $bill->currency_code, true)</td>
            <td class="total">@money($item->total, $bill->currency_code, true)</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="row">
    <div class="col-58">
        @if ($bill->notes)
        <table class="text" style="page-break-inside: avoid;">
            <tr><th>{{ trans_choice('general.notes', 2) }}</th></tr>
            <tr><td>{{ $bill->notes }}</td></tr>
        </table>
        @endif
    </div>
    <div class="col-42">
        <table class="text" style="page-break-inside: avoid;">
            <tbody>
            @foreach ($bill->totals as $total)
                @if ($total->code != 'total')
                    <tr>
                        <th>{{ trans($total->title) }}:</th>
                        <td class="text-right">@money($total->amount, $bill->currency_code, true)</td>
                    </tr>
                @else
                    @if ($bill->paid)
                        <tr class="text-success">
                            <th>{{ trans('invoices.paid') }}:</th>
                            <td class="text-right">- @money($bill->paid, $bill->currency_code, true)</td>
                        </tr>
                    @endif
                    <tr>
                        <th>{{ trans($total->name) }}:</th>
                        <td class="text-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
