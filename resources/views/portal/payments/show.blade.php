@extends('layouts.portal')

@section('title', trans_choice('general.payments', 1))

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="m-0 float-right">@date($payment->paid_at)</h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <a class="text-sm font-weight-600">{{ trans('general.from') }}:</a> <a class="text-xs long-texts"> {{ setting('company.name') }}</a>
                        </div>

                        <div class="card-body d-grid">
                            <a class="text-sm font-weight-600">{{ trans('general.address') }}:</a>
                            <a class="text-xs long-texts">{{ setting('company.address') }}</a>
                            <div class="dropdown-divider"></div>

                            <a class="text-sm font-weight-600">{{ trans('general.phone') }}:</a>
                            <a class="text-xs long-texts">{{ setting('company.phone') }}</a>
                            <div class="dropdown-divider"></div>

                            <a class="text-sm font-weight-600">{{ trans('general.email') }}:</a>
                            <a class="text-xs long-texts">{{ setting('company.email') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <a class="text-sm font-weight-600">{{ trans('general.to') }}:</a> <a class="text-xs long-texts"> {{ $payment->contact->name }}</a>
                        </div>

                        <div class="card-body d-grid">
                            <a class="text-sm font-weight-600">{{ trans('general.address') }}:</a>
                            <a class="text-xs long-texts">{{ $payment->contact->address }}</a>
                            <div class="dropdown-divider"></div>

                            <a class="text-sm font-weight-600">{{ trans('general.phone') }}:</a>
                            <a class="text-xs long-texts">{{ $payment->contact->phone }}</a>
                            <div class="dropdown-divider"></div>

                            <a class="text-sm font-weight-600">{{ trans('general.email') }}:</a>
                            <a class="text-xs long-texts">{{ $payment->contact->email }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($payment->description)
                <div class="row">
                    <div class="col-md-12">
                        <p class="lead">{{ trans('general.description') }}:</p>

                        <p class="text-muted">{{ $payment->description }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>{{ trans('general.amount') }}</th>
                        <th>{{ trans_choice('general.payment_methods', 1) }}</th>
                        <th>{{ trans('general.description') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>@money($payment->amount, $payment->currency_code, true)</td>
                        <td>{{ $payment_methods[$payment->payment_method] }}</td>
                        <td>{{ $payment->description }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if ($payment->attachment)
            <div class="card-footer">
                <ul class="mailbox-attachments">
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
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/customers/payments.js?v=' . version('short')) }}"></script>
@endpush
