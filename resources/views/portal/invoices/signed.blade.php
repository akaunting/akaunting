@extends('layouts.signed')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('new_button')
    <span class="new-button"><a href="{{ route('portal.dashboard') }}" class="btn btn-success btn-sm"><span class="fa fa-user"></span> &nbsp;{{ trans('invoices.all_invoices') }}</a></span>
@endsection

@section('content')
    <div class="card">
        <div class="card-header status-{{ $invoice->status->label }}">
            <h3 class="text-white mb-0 float-right pr-4">{{ trans('invoices.status.' . $invoice->status->code) }}</h3>
        </div>

        <div class="card-body">
                <div class="row mx--4">
                        <div class="col-md-7 border-bottom-1">
                            <div class="table-responsive mt-2">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>
                                        @if (setting('company.logo'))
                                            <img src="{{ Storage::url(setting('company.logo')) }}" />
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
                                        {{ setting('company.address') }}
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
                                        {{ $invoice->contact_address }}
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
        </div>

        <div class="row">
            <div class="col-md-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th class="pl-5">{{ trans_choice('general.items', 1) }}</th>
                            <th class="text-center">{{ trans('invoices.quantity') }}</th>
                            <th class="text-center pl-7">{{ trans('invoices.price') }}</th>
                            <th class="text-right pr-5">{{ trans('invoices.total') }}</th>
                        </tr>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td class="pl-5">{{ $item->name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-center pl-7">@money($item->price, $invoice->currency_code, true)</td>
                                <td class="text-right pr-5">@money($item->total, $invoice->currency_code, true)</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    @if ($invoice->notes)
                        <p class="form-control-label pl-4">{{ trans_choice('general.notes', 2) }}</p>
                        <p class="form-control text-muted show-note ml-4">{{ $invoice->notes }}</p>
                    @endif
                </div>
                <div class="col-md-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach($invoice->totals as $total)
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
                    @if($invoice->invoice_status_code != 'paid')
                        @if ($payment_methods)
                            {!! Form::open([
                                'id' => 'invoice-payment',
                                'role' => 'form',
                                'autocomplete' => "off",
                                'novalidate' => 'true'
                            ]) !!}
                                {{ Form::selectGroup('payment_method', '', 'fas fa-wallet', $payment_methods, '', ['change' => 'onChangePaymentMethodSigned', 'id' => 'payment-method', 'class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])], 'mb-0') }}
                                {!! Form::hidden('invoice_id', $invoice->id, ['v-model' => 'form.invoice_id']) !!}
                            {!! Form::close() !!}
                        @endif
                    @endif
                </div>
                <div class="col-md-8 text-right">
                    <a href="{{ $print_action }}" target="_blank" class="btn btn-success">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <a href="{{ $pdf_action }}" class="btn btn-white" data-toggle="tooltip" title="{{ trans('invoices.download_pdf') }}">
                        <i class="fa fa-file-pdf"></i>&nbsp; {{ trans('general.download') }}
                    </a>
                </div>

                <div class="col-md-12" id="confirm" >
                    <component v-bind:is="method_show_html" @interface="onRedirectConfirm"></component>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_start')
    <script src="{{ asset('public/js/portal/invoices.js?v=' . version('short')) }}"></script>
    <script>
    var payment_action_path = {!! json_encode($payment_actions) !!};
    </script>
@endpush
