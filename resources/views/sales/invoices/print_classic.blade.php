@extends('layouts.print')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    <div class="row">
        <div class="col-58">
            <div class="text company">
                <img src="{{ $logo }}" class="c-logo" alt="{{ setting('company.name') }}"/>
            </div>
        </div>

        <div class="col-42">
            <div class="text company">
                <strong>{{ setting('company.name') }}</strong><br>

                <p>{!! nl2br(setting('company.address')) !!}</p>

                <p>
                    @if (setting('company.tax_number'))
                        {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                    @endif
                </p>

                <p>
                    @if (setting('company.phone'))
                        {{ setting('company.phone') }}
                    @endif
                </p>

                <p>{{ setting('company.email') }}</p>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-33">
            <hr class="invoice-classic-line mb-1 mt-4" style="background-color:{{ setting('invoice.color') }};">
            <hr class="invoice-classic-line" style="background-color:{{ setting('invoice.color') }};">
        </div>

        <div class="col-33">
            <div class="invoice-classic-frame ml-1">
                <div class="invoice-classic-inline-frame text-center">
                    @stack('invoice_number_input_start')
                        <div class="text company">
                            <strong>{{ trans('invoices.invoice_number') }}:</strong><br>
                            {{ $invoice->invoice_number }}
                        </div>
                    @stack('invoice_number_input_end')
                </div>
            </div>
        </div>

        <div class="col-33">
            <hr class="invoice-classic-line mb-1 mt-4" style="background-color:{{ setting('invoice.color') }};">
            <hr class="invoice-classic-line" style="background-color:{{ setting('invoice.color') }};">
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-58">
            <div class="text company">
                <strong>{{ trans('invoices.bill_to') }}</strong><br>
                @stack('name_input_start')
                    <strong>{{ $invoice->contact_name }}</strong><br>
                @stack('name_input_end')

                @stack('address_input_start')
                    <p>{!! nl2br($invoice->contact_address) !!}</p>
                @stack('address_input_end')

                @stack('tax_number_input_start')
                    <p>
                        @if ($invoice->contact_tax_number)
                            {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}
                        @endif
                    </p>
                @stack('tax_number_input_end')

                @stack('phone_input_start')
                    <p>
                        @if ($invoice->contact_phone)
                            {{ $invoice->contact_phone }}
                        @endif
                    </p>
                @stack('phone_input_end')

                @stack('email_start')
                    <p>{{ $invoice->contact_email }}</p>
                @stack('email_input_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text company">
                @stack('order_number_input_start')
                    @if ($invoice->order_number)
                        <strong>{{ trans('invoices.order_number') }}:</strong>
                        <span class="float-right">{{ $invoice->order_number }}</span><br><br>
                    @endif
                @stack('order_number_input_end')

                @stack('invoiced_at_input_start')
                    <strong>{{ trans('invoices.invoice_date') }}:</strong>
                    <span class="float-right">@date($invoice->invoiced_at)</span><br><br>
                @stack('invoiced_at_input_end')

                @stack('due_at_input_start')
                    <strong>{{ trans('invoices.payment_due') }}:</strong>
                    <span class="float-right">@date($invoice->due_at)</span><br><br>
                @stack('due_at_input_end')

                @foreach ($invoice->totals_sorted as $total)
                    @if ($total->code == 'total')
                        <strong>{{ trans($total->name) }}:</strong>
                        <span class="float-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</span><br><br>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-100">
            <div class="text">
                <table class="c-lines">
                    <thead>
                        <tr>
                            @stack('name_th_start')
                                <th class="text-left item">{{ trans_choice($text_override['items'], 2) }}</th>
                            @stack('name_th_end')

                            @stack('quantity_th_start')
                                <th class="quantity">{{ trans($text_override['quantity']) }}</th>
                            @stack('quantity_th_end')

                            @stack('price_th_start')
                                <th class="price">{{ trans($text_override['price']) }}</th>
                            @stack('price_th_end')

                            @stack('total_th_start')
                                <th class="total">{{ trans('invoices.total') }}</th>
                            @stack('total_th_end')
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $invoice_item)
                            <tr>
                                @stack('name_td_start')
                                    <td class="item">
                                        {{ $invoice_item->name }}
                                        @if (!empty($invoice_item->item->description))
                                            <br><small>{!! \Illuminate\Support\Str::limit($invoice_item->item->description, 500) !!}</small>
                                        @endif
                                    </td>
                                @stack('name_td_end')

                                @stack('quantity_td_start')
                                    <td class="quantity">{{ $invoice_item->quantity }}</td>
                                @stack('quantity_td_end')

                                @stack('price_td_start')
                                    <td class="price">@money($invoice_item->price, $invoice->currency_code, true)</td>
                                @stack('price_td_end')

                                @stack('total_td_start')
                                    <td class="total">@money($invoice_item->total, $invoice->currency_code, true)</td>
                                @stack('total_td_end')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-58">
            <div class="text company">
                @stack('notes_input_start')
                    @if ($invoice->notes)
                        <strong>{{ trans_choice('general.notes', 2) }}</strong><br><br>
                        {{ $invoice->notes }}
                    @endif
                @stack('notes_input_end')
            </div>
        </div>

        <div class="col-42 float-right text-right">
            <div class="text company pr-2">
                @foreach ($invoice->totals_sorted as $total)
                    @if ($total->code != 'total')
                        @stack($total->code . '_td_start')
                            <div class="border-top-dashed py-2">
                                <strong class="float-left">{{ trans($total->title) }}:</strong>
                                <span>@money($total->amount, $invoice->currency_code, true)</span>
                            </div>
                        @stack($total->code . '_td_end')
                    @else
                        @if ($invoice->paid)
                            <div class="border-top-dashed py-2">
                                <strong class="float-left">{{ trans('invoices.paid') }}:</strong>
                                <span>- @money($invoice->paid, $invoice->currency_code, true)</span>
                            </div>
                        @endif
                        @stack('grand_total_td_start')
                            <div class="border-top-dashed py-2">
                                <strong class="float-left">{{ trans($total->name) }}:</strong>
                                <span>@money($total->amount - $invoice->paid, $invoice->currency_code, true)</span>
                            </div>
                        @stack('grand_total_td_end')
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @if ($invoice->footer)
        <div class="row mt-1">
            <div class="col-100">
                <div class="text company">
                    <strong>{!! $invoice->footer !!}</strong>
                </div>
            </div>
        </div>
    @endif
@endsection
