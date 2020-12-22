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
                    <div class="card list-group">
                        <li class="list-group-item list-group-item-action active">
                           <b>{{ trans('general.from') }}:</b> {{ setting('company.name') }}
                        </li>
                        <li class="list-group-item"><b>{{ trans('general.address') }}:</b> {{ setting('company.address') }}</li>
                        <li class="list-group-item"><b>{{ trans('general.phone') }}:</b> {{ setting('company.phone') }}</li>
                        <li class="list-group-item"><b>{{ trans('general.email') }}:</b> {{ setting('company.email') }}</li>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card list-group">
                        <li class="list-group-item list-group-item-action active">
                           <b>{{ trans('general.to') }}:</b> {{ $payment->contact->name }}
                        </li>
                        <li class="list-group-item"><b>{{ trans('general.address') }}:</b> {{ $payment->contact->address }}</li>
                        <li class="list-group-item"><b>{{ trans('general.phone') }}:</b> {{ $payment->contact->phone }}</li>
                        <li class="list-group-item"><b>{{ trans('general.email') }}:</b> {{ $payment->contact->email }}</li>
                    </div>
                </div>

                <div class="col-md-6">
                    @if ($payment->description)
                        <p class="form-control-label">{{ trans('general.description') }}</p>
                        <p class="text-muted long-texts">{{ $payment->description }}</p>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <div class="row align-items-center">
                                <div class="col-12 text-nowrap">
                                    <h4 class="mb-0">{{ trans_choice('general.payments', 1) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-flush table-hover">
                                <thead class="thead-light">
                                    <tr class="row table-head-line">
                                        <th class="col-xs-4 col-sm-2 text-right">{{ trans('general.amount') }}</th>
                                        <th class="col-xs-4 col-sm-3 text-left">{{ trans_choice('general.payment_methods', 1) }}</th>
                                        <th class="col-xs-4 col-sm-7 text-left long-texts">{{ trans('general.description') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="row border-top-1 tr-py">
                                        <td class="col-xs-4 col-sm-2 text-right">@money($payment->amount, $payment->currency_code, true)</td>
                                        <td class="col-xs-4 col-sm-3 text-left">{{ $payment_methods[$payment->payment_method] }}</td>
                                        <td class="col-xs-4 col-sm-7 text-left long-texts">{{ $payment->description }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($payment->attachment)
            <div class="card-footer">
                <div class="row float-right">
                    <div class="col-md-12">
                        @if (1)
                           <div class="card mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <i class="far fa-file-pdf display-3"></i>
                                        </div>

                                        <div class="col-auto">
                                            <a class="btn btn-sm btn-icon btn-white" type="button">
                                                <span class="btn-inner--icon">
                                                    <i class="fas fa-paperclip"></i>
                                                    {{ basename($payment->attachment) }}
                                                </span>
                                            </a>

                                            <a class="btn btn-sm btn-icon btn-info text-white float-right" type="button">
                                                <span class="btn-inner--icon">
                                                    <i class="fas fa-file-download"></i>
                                                    {{ basename($payment->attachment) }}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                           <div class="card mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            @if($payment->attachment)
                                                <img src="public/img/invoice_templates/classic.png" alt="Attachment">
                                            @else
                                                <i class="far fa-file-image display-3"></i>
                                            @endif
                                        </div>

                                        <div class="col-auto">
                                            <a class="btn btn-sm btn-icon btn-white" type="button">
                                                <span class="btn-inner--icon">
                                                    <i class="fas fa-camera"></i>
                                                    {{ basename($payment->attachment) }}
                                                </span>
                                            </a>

                                            <a class="btn btn-sm btn-icon btn-info text-white float-right" type="button">
                                                <span class="btn-inner--icon">
                                                    <i class="fas fa-file-download"></i>
                                                    {{ basename($payment->attachment) }}
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/portal/payments.js?v=' . version('short')) }}"></script>
@endpush
