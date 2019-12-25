@extends('layouts.print')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    <div class="row">
        <div class="col-md-7">
            <img class="mt-4" src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
        </div>
        <div class="col-md-5 text-right">
            <p class="mb-0 mt-4 font-weight-600">
                {{ setting('company.name') }}
            </p>

            <p class="mb-0">
                {!! nl2br(setting('company.address')) !!}
            </p>

            <p class="mb-0">
                @if (setting('company.tax_number'))
                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                @endif
            </p>

            <p class="mb-0 mt-3">
                @if (setting('company.phone'))
                    {{ setting('company.phone') }}
                @endif
            </p>

            <p class="mb-0">
                {{ setting('company.email') }}
            </p>
        </div>
    </div>

    <div class="row my-4">
        <div class="col-md-4">
            <hr class="bg-default invoice-classic-line mb-1 mt-5">
            <hr class="bg-default invoice-classic-line my-0">
        </div>
        <div class="col-md-4 text-center">
            <div class="invoice-classic-frame">
                <div class="invoice-classic-inline-frame">
                    @stack('invoice_number_input_start')
                        <p class="mt-4">
                            <b>{{ trans('invoices.invoice_number') }}:</b>
                            {{ $invoice->invoice_number }}
                        </p>
                    @stack('invoice_number_input_end')
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <hr class="bg-default invoice-classic-line mb-1 mt-5">
            <hr class="bg-default invoice-classic-line my-0">
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-7">

            <h4>{{ trans('invoices.bill_to') }}</h4>
            @stack('name_input_start')
                <strong class="d-block">{{ $invoice->contact_name }}</strong>
            @stack('name_input_end')

            @stack('address_input_start')
                <p class="mb-0">
                    {!! nl2br($invoice->contact_address) !!}
                </p>
            @stack('address_input_end')

            @stack('tax_number_input_start')
                <p class="mb-0">
                    @if ($invoice->contact_tax_number)
                        {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}<br>
                    @endif
                </p>
            @stack('tax_number_input_end')

            @stack('phone_input_start')
                <p class="mb-0 mt-3">
                    @if ($invoice->contact_phone)
                        {{ $invoice->contact_phone }}
                    @endif
                </p>
            @stack('phone_input_end')

            @stack('email_start')
                <p class="mb-0">
                    {{ $invoice->contact_email }}
                </p>
            @stack('email_input_end')

        </div>
        <div class="col-md-5 text-right">
            @stack('order_number_input_start')
                @if ($invoice->order_number)
                    <p>
                        <b>{{ trans('invoices.order_number') }}:</b>
                        {{ $invoice->order_number }}
                    </p>
                @endif
            @stack('order_number_input_end')

            @stack('invoiced_at_input_start')
                <p>
                    <b>{{ trans('invoices.invoice_date') }}:</b>
                    @date($invoice->invoiced_at)
                </p>
            @stack('invoiced_at_input_end')

            @stack('due_at_input_start')
                <p>
                    <b>{{ trans('invoices.payment_due') }}:</b>
                    @date($invoice->due_at)
                </p>
            @stack('due_at_input_end')

            @foreach ($invoice->totals as $total)
                @if ($total->code == 'total')
                    <p class="bg-light border-radius-5 float-right text-center w-50">
                        <b>{{ trans($total->name) }}:</b>
                        @money($total->amount - $invoice->paid, $invoice->currency_code, true)
                    </p>
                @endif
            @endforeach
        </div>
    </div>

    <div class="row show-table">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr class="row border-dashed">
                            @stack('name_th_start')
                                <th class="col-xs-4 col-sm-3 pl-5">{{ trans_choice($text_override['items'], 2) }}</th>
                            @stack('name_th_end')

                            @stack('quantity_th_start')
                                <th class="col-xs-4 col-sm-3 text-center">{{ trans($text_override['quantity']) }}</th>
                            @stack('quantity_th_end')

                            @stack('price_th_start')
                                <th class="col-sm-3 text-center d-none d-sm-block pl-5">{{ trans($text_override['price']) }}</th>
                            @stack('price_th_end')

                            @stack('total_th_start')
                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('invoices.total') }}</th>
                            @stack('total_th_end')
                        </tr>
                        @foreach($invoice->items as $item)
                            <tr class="row border-dashed">
                                @stack('name_td_start')
                                    <td class="col-xs-4 col-sm-3 pl-5">
                                        {{ $item->name }}
                                        @if ($item->desc)
                                            <br><small>{!! $item->desc !!}</small>
                                        @endif
                                    </td>
                                @stack('name_td_end')

                                @stack('quantity_td_start')
                                    <td class="col-xs-4 col-sm-3 text-center">{{ $item->quantity }}</td>
                                @stack('quantity_td_end')

                                @stack('price_td_start')
                                    <td class="col-sm-3 text-center d-none d-sm-block pl-5">@money($item->price, $invoice->currency_code, true)</td>
                                @stack('price_td_end')

                                @stack('total_td_start')
                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($item->total, $invoice->currency_code, true)</td>
                                @stack('total_td_end')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-7">
            <div class="table-responsive">
                @stack('notes_input_start')
                    @if ($invoice->notes)
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                        <p class="form-control text-muted">{{ $invoice->notes }}</p>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                @stack('notes_input_end')
            </div>
        </div>
        <div class="col-md-5">
            <div class="table-responsive">
                <table class="table table-borderless border-dashed">
                    <tbody>
                         @foreach ($invoice->totals as $total)
                             @if ($total->code != 'total')
                                 @stack($total->code . '_td_start')
                                     <tr class="border-dashed">
                                        <th>
                                            {{ trans($total->title) }}:
                                        </th>
                                        <td class="text-right">
                                            @money($total->amount, $invoice->currency_code, true)
                                        </td>
                                    </tr>
                                 @stack($total->code . '_td_end')
                             @else
                                 @if ($invoice->paid)
                                     <tr class="border-dashed">
                                        <th>
                                            {{ trans('invoices.paid') }}:
                                        </th>
                                        <td class="text-right">
                                            - @money($invoice->paid, $invoice->currency_code, true)
                                        </td>
                                    </tr>
                                 @endif
                                 @stack('grand_total_td_start')
                                     <tr class="border-dashed">
                                        <th>
                                            {{ trans($total->name) }}:
                                        </th>
                                        <td class="text-right">
                                            @money($total->amount - $invoice->paid, $invoice->currency_code, true)
                                        </td>
                                    </tr>
                                 @stack('grand_total_td_end')
                             @endif
                        @endforeach
                    </tbody>
               </table>
            </div>
        </div>
    </div>

    @if ($invoice->footer)
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th>
                                    {!! $invoice->footer !!}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
