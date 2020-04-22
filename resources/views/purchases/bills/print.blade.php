@extends('layouts.print')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    <div class="row border-bottom-1">
        <div class="col-58">
            <div class="text company">
                @if (!empty($bill->contact->logo) && !empty($bill->contact->logo->id))
                    <img class="d-logo" src="{{ Storage::url($bill->contact->logo->id) }}" alt="{{ $bill->contact_name }}"/>
                @else
                    <img class="d-logo" src="{{ $logo }}" alt="{{ $bill->contact_name }}"/>
                @endif
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

    <div class="row">
        <div class="col-58">
            <div class="text company">
                <br>
                <strong>{{ trans('bills.bill_from') }}</strong><br>
                @stack('name_input_start')
                    <strong class="d-block">{{ $bill->contact_name }}</strong><br>
                @stack('name_input_end')

                @stack('address_input_start')
                    <p>{!! nl2br($bill->contact_address) !!}</p>
                @stack('address_input_end')

                @stack('tax_number_input_start')
                    <p>
                        @if ($bill->contact_tax_number)
                            {{ trans('general.tax_number') }}: {{ $bill->contact_tax_number }}
                        @endif
                    </p>
                @stack('tax_number_input_end')

                @stack('phone_input_start')
                    <p>
                        @if ($bill->contact_phone)
                            {{ $bill->contact_phone }}
                        @endif
                    </p>
                @stack('phone_input_end')

                @stack('email_start')
                    <p>
                        {{ $bill->contact_email }}
                    </p>
                @stack('email_input_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text company">
                <br>
                @stack('bill_number_input_start')
                    <strong>{{ trans('bills.bill_number') }}:</strong>
                    <span class="float-right">{{ $bill->bill_number }}</span><br><br>
                @stack('bill_number_input_end')

                @stack('order_number_input_start')
                    @if ($bill->order_number)
                        <strong>{{ trans('bills.order_number') }}:</strong>
                        <span class="float-right">{{ $bill->order_number }}</span><br><br>
                    @endif
                @stack('order_number_input_end')

                @stack('billed_at_input_start')
                    <strong>{{ trans('bills.bill_date') }}:</strong>
                    <span class="float-right">@date($bill->billed_at)</span><br><br>
                @stack('billed_at_input_end')

                @stack('due_at_input_start')
                    <strong>{{ trans('bills.payment_due') }}:</strong>
                    <span class="float-right">@date($bill->due_at)</span><br><br>
                @stack('due_at_input_end')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-100">
            <div class="text">
                <table class="lines">
                    @foreach($bill as $item)
                        <thead style="background-color:{{ setting('invoice.color') }} !important; -webkit-print-color-adjust: exact;">
                    @endforeach
                        <tr>
                            @stack('name_th_start')
                                <th class="item text-left text-white">{{ trans_choice('general.items', 1) }}</th>
                            @stack('name_th_end')

                            @stack('quantity_th_start')
                                <th class="quantity text-white">{{ trans('bills.quantity') }}</th>
                            @stack('quantity_th_end')

                            @stack('price_th_start')
                                <th class="price text-white">{{ trans('bills.price') }}</th>
                            @stack('price_th_end')

                            @stack('total_th_start')
                                <th class="total text-white">{{ trans('bills.total') }}</th>
                            @stack('total_th_end')
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($bill->items as $bill_item)
                            <tr>
                                @stack('name_td_start')
                                    <td class="item">
                                        {{ $bill_item->name }}
                                        @if (!empty($bill_item->item->description))
                                            <br><small>{!! \Illuminate\Support\Str::limit($bill_item->item->description, 500) !!}<small>
                                        @endif
                                    </td>
                                @stack('name_td_end')

                                @stack('quantity_td_start')
                                    <td class="quantity">{{ $bill_item->quantity }}</td>
                                @stack('quantity_td_end')

                                @stack('price_td_start')
                                    <td class="price">@money($bill_item->price, $bill->currency_code, true)</td>
                                @stack('price_td_end')

                                @stack('total_td_start')
                                    <td class="total">@money($bill_item->total, $bill->currency_code, true)</td>
                                @stack('total_td_end')
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row mt-9">
        <div class="col-58">
            <div class="text company">
                @stack('notes_input_start')
                    @if ($bill->notes)
                        <br>
                        <strong>{{ trans_choice('general.notes', 2) }}</strong><br><br>
                        {{ $bill->notes }}
                    @endif
                @stack('notes_input_end')
            </div>
        </div>

        <div class="col-42">
            <div class="text company pr-2">
                @foreach ($bill->totals_sorted as $total)
                        @if ($total->code != 'total')
                            @stack($total->code . '_td_start')
                                <div class="border-top-1 py-2">
                                    <strong>{{ trans($total->title) }}:</strong>
                                    <span class="float-right">@money($total->amount, $bill->currency_code, true)</span><br><br>
                                </div>
                            @stack($total->code . '_td_end')
                        @else
                            @if ($bill->paid)
                                <div class="border-top-1 py-2">
                                    <strong>{{ trans('invoices.paid') }}:</strong>
                                    <span class="float-right">- @money($bill->paid, $bill->currency_code, true)
                                    </span><br><br>
                                </div>
                            @endif
                            @stack('grand_total_td_start')
                                <div class="border-top-1 py-2">
                                    <strong>{{ trans($total->name) }}:</strong>
                                    <span class="float-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</span>
                                </div>
                            @stack('grand_total_td_end')
                        @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
