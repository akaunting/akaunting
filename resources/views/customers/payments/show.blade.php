@extends('layouts.customer')

@section('title', trans_choice('general.invoices', 1))

@section('content')
    <div class="box box-success">
        <div class="invoice"><span class="badge" style="background-color : {{ $payment->category->color }}">{{ $payment->category->name }}</span>
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="fa fa-globe"></i> {{ $payment->customer->name }}
                        <small class="pull-right">{{ trans('general.date') }}: {{ Date::parse($payment->paid_at)->format($date_format) }}</small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    {{ trans('general.from') }}
                    <address>
                        <strong>{{ setting('general.company_name') }}</strong><br>
                        {{ setting('general.company_address') }}<br>
                        {{ trans('general.phone') }}: (804) 123-5432<br>
                        {{ trans('general.email') }}: {{ setting('general.company_email') }}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    {{ trans('general.to') }}
                    <address>
                        <strong>{{ $payment->customer->name }}</strong><br>
                        {{ $payment->customer->address }}<br>
                        {{ trans('general.phone') }}: {{ $payment->customer->phone }}<br>
                        {{ trans('general.email') }}: {{ $payment->customer->email }}
                    </address>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>{{ trans('invoices.payment_due') }}:</b> {{ Date::parse($payment->paid_at)->format($date_format) }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{ trans_choice('general.categories', 1) }}</th>
                            <th>{{ trans_choice('general.payment_methods', 1) }}</th>
                            <th>{{ trans('general.reference') }}</th>
                            <th>{{ trans('general.amount') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $payment->category->name }}</td>
                            <td>{{ $payment_methods[$payment->payment_method] }}</td>
                            <td>{{ $payment->reference }}</td>
                            <td>@money($payment->amount, $payment->currency_code, true)</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($payment->description)
            <div class="row">
                <div class="col-xs-12">
                    <p class="lead">{{ trans('general.description') }}:</p>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ $payment->description }}
                    </p>
                </div>
            </div>
            @endif

            @if ($payment->attachment)
            <div class="box-footer">
                <ul class="mailbox-attachments clearfix">
                    @if (1)
                    <li>
                        <span class="mailbox-attachment-icon"><i class="fa fa-file-pdf-o"></i></span>

                        <div class="mailbox-attachment-info">
                            <a href="#" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ basename($payment->attachment) }}</a>
                            <span class="mailbox-attachment-size">
                              <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                            </span>
                        </div>
                    </li>
                    @else
                    <li>
                        <span class="mailbox-attachment-icon has-img"><img src="{{ asset($payment->attachment) }}" alt="Attachment"></span>

                        <div class="mailbox-attachment-info">
                            <a href="#" class="mailbox-attachment-name"><i class="fa fa-camera"></i> {{ basename($payment->attachment) }}</a>
                            <span class="mailbox-attachment-size">
                              <a href="#" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                            </span>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>
    </div>
@endsection

