@extends('layouts.bill')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    <div class="mt-6">
        <div class="row">
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    @if ($logo)
                                        <img src="{{ $logo }}"/>
                                    @endif
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                    {{ setting('company.name') }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    {!! nl2br(setting('company.address')) !!}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @if (setting('company.tax_number'))
                                        {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @if (setting('company.phone'))
                                        {{ setting('company.phone') }}
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    {{ setting('company.email') }}
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dropdown-divider"></div>

        <div class="row">
            <div class="col-md-7">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>
                                   {{ trans('bills.bill_from') }}
                                    @stack('name_input_start')
                                        <strong class="d-block">{{ $bill->contact_name }}</strong><br>
                                    @stack('name_input_end')
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @stack('address_input_start')
                                        {!! nl2br($bill->contact_address) !!}
                                    @stack('address_input_end')
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @stack('tax_number_input_start')
                                        @if ($bill->contact_tax_number)
                                            {{ trans('general.tax_number') }}: {{ $bill->contact_tax_number }}
                                        @endif
                                    @stack('tax_number_input_end')
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @stack('phone_input_start')
                                        @if ($bill->contact_phone)
                                            {{ $bill->contact_phone }}
                                        @endif
                                    @stack('phone_input_end')
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    @stack('email_start')
                                        {{ $bill->contact_email }}
                                    @stack('email_input_end')
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            @stack('bill_number_input_start')
                                <tr>
                                    <th>{{ trans('bills.bill_number') }}:</th>
                                    <td class="text-right">{{ $bill->bill_number }}</td>
                                </tr>
                            @stack('bill_number_input_end')

                            @stack('order_number_input_start')
                                @if ($bill->order_number)
                                    <tr>
                                        <th>{{ trans('bills.order_number') }}:</th>
                                        <td class="text-right">{{ $bill->order_number }}</td>
                                    </tr>
                                @endif
                            @stack('order_number_input_end')

                            @stack('billed_at_input_start')
                                <tr>
                                    <th>{{ trans('bills.bill_date') }}:</th>
                                    <td class="text-right">@date($bill->billed_at)</td>
                                </tr>
                            @stack('billed_at_input_end')

                            @stack('due_at_input_start')
                                <tr>
                                    <th>{{ trans('bills.payment_due') }}:</th>
                                    <td class="text-right">@date($bill->due_at)</td>
                                </tr>
                            @stack('due_at_input_end')
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    @stack('actions_th_start')
                    @stack('actions_th_end')

                    @stack('name_th_start')
                        <th>{{ trans_choice('general.items', 1) }}</th>
                    @stack('name_th_end')

                    @stack('quantity_th_start')
                        <th class="text-center">{{ trans('bills.quantity') }}</th>
                    @stack('quantity_th_end')

                    @stack('price_th_start')
                        <th class="text-center pr-5">{{ trans('bills.price') }}</th>
                    @stack('price_th_end')

                    @stack('taxes_th_start')
                    @stack('taxes_th_end')

                    @stack('total_th_start')
                        <th class="text-right">{{ trans('bills.total') }}</th>
                    @stack('total_th_end')
                </tr>
            </thead>
            <tbody>
                @foreach($bill->items as $item)
                    <tr>
                        @stack('name_td_start')
                            <td>{{ $item->name }}</td>
                        @stack('name_td_end')

                        @stack('quantity_td_start')
                            <td class="text-center">{{ $item->quantity }}</td>
                        @stack('quantity_td_end')

                        @stack('price_td_start')
                            <td class="text-center pr-5">@money($item->price, $bill->currency_code, true)</td>
                        @stack('price_td_end')

                        @stack('total_td_start')
                            <td class="text-right">@money($item->total, $bill->currency_code, true)</td>
                        @stack('total_td_end')
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-7">
                <div class="table-responsive">
                    @stack('notes_input_start')
                        @if ($bill->notes)
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans_choice('general.notes', 2) }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ $bill->notes }}
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
                    <table class="table table-borderless">
                        <tbody>
                            @foreach ($bill->totals as $total)
                                @if ($total->code != 'total')
                                    @stack($total->code . '_td_start')
                                        <tr>
                                            <th>
                                                {{ trans($total->title) }}:
                                            </th>
                                            <td class="text-right">
                                                @money($total->amount, $bill->currency_code, true)
                                            </td>
                                        </tr>
                                    @stack($total->code . '_td_end')
                                @else
                                    @if ($bill->paid)
                                        <tr class="text-success">
                                            <th>
                                                {{ trans('invoices.paid') }}:
                                            </th>
                                            <td class="text-right">
                                                - @money($bill->paid, $bill->currency_code, true)
                                            </td>
                                        </tr>
                                    @endif
                                    @stack('grand_total_td_start')
                                        <tr>
                                            <th>
                                                {{ trans($total->name) }}:
                                            </th>
                                            <td class="text-right">
                                                @money($total->amount - $bill->paid, $bill->currency_code, true)
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
    </div>
@endsection
