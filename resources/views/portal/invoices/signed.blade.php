@extends('layouts.signed')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('new_button')
    <a href="{{ route('portal.dashboard') }}" class="btn btn-success btn-sm header-button-top"><span class="fa fa-user"></span> &nbsp;{{ trans('invoices.all_invoices') }}</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header status-{{ $invoice->status_label }}">
            <h3 class="text-white mb-0 float-right pr-4">{{ trans('invoices.statuses.' . $invoice->status) }}</h3>
        </div>

        <div class="card-body">
            <div class="row mx--4">
                <div class="col-md-7 border-bottom-1">
                    <div class="table-responsive mt-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        <img src="{{ $logo }}" alt="{{ setting('company.name') }}"/>
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
                                            {{ setting('company.phone') }}<br>
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

            <div class="row">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('invoices.bill_to') }}
                                        <strong class="d-block">{{ $invoice->contact_name }}</strong>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {!! nl2br($invoice->contact_address) !!}
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($invoice->contact_tax_number)
                                            {{ trans('general.tax_number') }}: {{ $invoice->contact_tax_number }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        @if ($invoice->contact_phone)
                                            {{ $invoice->contact_phone }}<br>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        {{ $invoice->contact_email }}
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
                                    <td class="text-right">@date($invoice->due_at)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row show-table">
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr class="row">
                                <th class="col-xs-4 col-sm-5 pl-5">{{ trans_choice('general.items', 1) }}</th>
                                <th class="col-xs-4 col-sm-1 text-center">{{ trans('invoices.quantity') }}</th>
                                <th class="col-sm-3 text-right d-none d-sm-block">{{ trans('invoices.price') }}</th>
                                <th class="col-xs-4 col-sm-3 text-right pr-5">{{ trans('invoices.total') }}</th>
                            </tr>
                            @foreach($invoice->items as $invoice_item)
                                <tr class="row">
                                    <td class="col-xs-4 col-sm-5 pl-5">
                                        {{ $invoice_item->name }}
                                        @if (!empty($invoice_item->item->description))
                                            <br><small class="text-pre-nowrap">{!! \Illuminate\Support\Str::limit($invoice_item->item->description, 500) !!}<small>
                                        @endif
                                    </td>
                                    <td class="col-xs-4 col-sm-1 text-center">{{ $invoice_item->quantity }}</td>
                                    <td class="col-sm-3 text-right d-none d-sm-block">@money($invoice_item->price, $invoice->currency_code, true)</td>
                                    <td class="col-xs-4 col-sm-3 text-right pr-5">@money($invoice_item->total, $invoice->currency_code, true)</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-7">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        @if ($invoice->notes)
                                            <p class="form-control-label">{{ trans_choice('general.notes', 2) }}</p>
                                            <p class="text-muted long-texts">{{ $invoice->notes }}</p>
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach($invoice->totals_sorted as $total)
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
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-4">
                    @if (!empty($payment_methods) && !in_array($invoice->status, ['paid', 'cancelled']))
                        {!! Form::open([
                            'id' => 'invoice-payment',
                            'role' => 'form',
                            'autocomplete' => "off",
                            'novalidate' => 'true',
                            'class' => 'mb-0',
                        ]) !!}
                            {{ Form::selectGroup('payment_method', '', 'fas fa-wallet', $payment_methods, '', ['change' => 'onChangePaymentMethodSigned', 'id' => 'payment-method', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])], 'mb-0') }}
                            {!! Form::hidden('invoice_id', $invoice->id, ['v-model' => 'form.invoice_id']) !!}
                        {!! Form::close() !!}
                    @endif
                </div>

                <div class="col-md-8 text-right">
                    <a href="{{ $print_action }}" target="_blank" class="btn btn-success header-button-top">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <a href="{{ $pdf_action }}" class="btn btn-white header-button-top" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                        <i class="fa fa-file-pdf"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                </div>

                <div class="col-md-12" id="confirm">
                    <component v-bind:is="method_show_html" @interface="onRedirectConfirm"></component>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
    <script type="text/javascript">
        var payment_action_path = {!! json_encode($payment_actions) !!};
    </script>
@endpush
