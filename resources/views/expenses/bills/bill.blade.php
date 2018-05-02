@extends('layouts.bill')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    <section class="bill">
        <div class="row invoice-header">
            <div class="col-xs-7">
                @if ($logo)
                    <img src="{{ $logo }}" class="invoice-logo" />
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

        <div class="row">
            <div class="col-xs-7">
                {{ trans('bills.bill_from') }}
                <address>
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
                </address>
            </div>
            <div class="col-xs-5">
                <div class="table-responsive">
                    <table class="table no-border">
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

        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <th>{{ trans_choice('general.items', 1) }}</th>
                        <th class="text-center">{{ trans('bills.quantity') }}</th>
                        <th class="text-right">{{ trans('bills.price') }}</th>
                        <th class="text-right">{{ trans('bills.total') }}</th>
                    </tr>
                    @foreach($bill->items as $item)
                        <tr>
                            <td>
                                {{ $item->name }}
                                @if ($item->sku)
                                    <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">@money($item->price, $bill->currency_code, true)</td>
                            <td class="text-right">@money($item->total, $bill->currency_code, true)</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-7">
                @if ($bill->notes)
                    <p class="lead">{{ trans_choice('general.notes', 2) }}:</p>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ $bill->notes }}
                    </p>
                @endif
            </div>
            <div class="col-xs-5">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        @foreach($bill->totals as $total)
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
        </div>
    </section>
@endsection
