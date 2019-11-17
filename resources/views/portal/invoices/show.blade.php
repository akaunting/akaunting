@extends('layouts.portal')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    @stack('invoice_start')
        <div class="card">
            @stack('invoice_status_start')
                <div class="card-header status-{{ $invoice->status->label }}">
                    <h3 class="text-white mb-0 float-right">{{ trans('invoices.status.' . $invoice->status->code) }}</h3>
                </div>
            @stack('invoice_status_end')

            <div class="card-body">
                @stack('invoice_header_start')
                    <div class="row mx--4">
                        <div class="col-md-7 border-bottom-1">
                            <div class="table-responsive mt-2">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                @if (setting('company.logo'))
                                                    <img src="{{ Storage::url(setting('company.logo')) }}"/>
                                                @else
                                                    <span class="avatar avatar-size rounded-circle bg-primary">
                                                        <i class="fas fa-building"></i>
                                                    </span>
                                                @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5 border-bottom-1">
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
                                                    {{ trans('general.tax_number') }}: {{ setting('company.tax_number') }}<br>
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
                @stack('invoice_header_end')

                @stack('invoice_information_start')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {{ trans('invoices.bill_to') }}
                                                @stack('name_input_start')
                                                    <strong class="d-block">{{ $invoice->contact_name }}</strong>
                                                @stack('name_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('address_input_start')
                                                    {!! nl2br($invoice->contact_address) !!}
                                                @stack('address_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('tax_number_input_start')
                                                    @if ($invoice->contact_tax_number)
                                                        {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}<br>
                                                    @endif
                                                @stack('tax_number_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('phone_input_start')
                                                    @if ($invoice->contact_phone)
                                                        {{ $invoice->contact_phone }}
                                                    @endif
                                                @stack('phone_input_end')
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                @stack('email_start')
                                                    {{ $invoice->contact_email }}
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
                                                <td class="text-right">@date($invoice->invoiced_at)</td>
                                            </tr>
                                        @stack('invoiced_at_input_end')

                                        @stack('due_at_input_start')
                                            <tr>
                                                <th>{{ trans('invoices.payment_due') }}:</th>
                                                <td class="text-right">@date($invoice->invoiced_at)</td>
                                            </tr>
                                        @stack('due_at_input_end')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('invoice_information_end')

                @stack('invoice_item_start')
                    <div class="row show-table">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr class="row">
                                            @stack('name_th_start')
                                                <th class="col-xs-4 col-sm-3 pl-5">{{ trans_choice($text_override['items'], 2) }}</th>
                                            @stack('name_th_end')

                                            @stack('quantity_th_start')
                                                <th class="col-xs-4 col-sm-3 text-center">{{ trans($text_override['quantity']) }}</th>
                                            @stack('quantity_th_end')

                                            @stack('price_th_start')
                                                <th class="col-sm-3 text-center hidden-sm pl-5">{{ trans($text_override['price']) }}</th>
                                            @stack('price_th_end')

                                            @stack('total_th_start')
                                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('invoices.total') }}</th>
                                            @stack('total_th_end')
                                        </tr>
                                        @foreach($invoice->items as $item)
                                            <tr class="row">
                                                @stack('name_td_start')
                                                    <td class="col-xs-4 col-sm-3 pl-5">{{ $item->name }}</td>
                                                @stack('name_td_end')

                                                @stack('quantity_td_start')
                                                    <td class="col-xs-4 col-sm-3 text-center">{{ $item->quantity }}</td>
                                                @stack('quantity_td_end')

                                                @stack('price_td_start')
                                                    <td class="col-sm-3 text-center hidden-sm pl-5">@money($item->price, $invoice->currency_code, true)</td>
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
                @stack('invoice_item_end')

                @stack('invoice_total_start')
                    <div class="row">
                        <div class="col-md-7">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        @stack('notes_input_start')
                                            <tr>
                                                <th>
                                                    @if ($invoice->notes)
                                                        <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                                        <p class="form-control text-muted show-note">{{ $invoice->notes }}</p>
                                                    @endif
                                                </th>
                                            </tr>
                                        @stack('notes_input_end')
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        @foreach ($invoice->totals as $total)
                                            @if ($total->code != 'total')
                                                @stack($total->code . '_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->title) }}:</th>
                                                        <td class="text-right">@money($total->amount, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @stack($total->code . '_td_end')
                                            @else
                                                @if ($invoice->paid)
                                                    <tr class="text-success">
                                                        <th>{{ trans('invoices.paid') }}:</th>
                                                        <td class="text-right">- @money($invoice->paid, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @endif
                                                @stack('grand_total_td_start')
                                                    <tr>
                                                        <th>{{ trans($total->name) }}:</th>
                                                        <td class="text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                                                    </tr>
                                                @stack('grand_total_td_end')
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @stack('invoice_total_end')
            </div>

            @stack('card_footer_start')
                <div class="card-footer">
                    <div class="float-right">
                        @stack('button_print_start')
                            <a href="{{ route('portal.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-success header-button-top">
                                <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                            </a>
                        @stack('button_print_end')

                        @stack('button_pdf_start')
                            <a href="{{ route('portal.invoices.pdf', $invoice->id) }}" class="btn btn-white header-button-top" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                                <i class="fa fa-file-pdf"></i>&nbsp; {{ trans('general.download') }}
                            </a>
                        @stack('button_pdf_end')
                    </div>
                    <div class="col-md-4">
                        @if($invoice->invoice_status_code != 'paid')
                            @if ($payment_methods)
                            {!! Form::open([
                                'id' => 'invoice-payment',
                                'role' => 'form',
                                'autocomplete' => "off",
                                'novalidate' => 'true'
                            ]) !!}
                                {{ Form::selectGroup('payment_method', '', 'money el-icon-money', $payment_methods, '', ['change' => 'onChangePaymentMethod', 'id' => 'payment-method', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])], 'col-md-12') }}
                                {!! Form::hidden('invoice_id', $invoice->id, ['v-model' => 'form.invoice_id']) !!}
                            {!! Form::close() !!}
                            @endif
                        @endif
                    </div>

                    <div id="confirm" class="col-md-12">
                        <component v-bind:is="method_show_html" @interface="onRedirectConfirm"></component>
                    </div>
                </div>
            @stack('card_footer_end')
        </div>
    @stack('invoice_end')
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
@endpush
