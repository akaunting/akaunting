@extends('layouts.admin')

@section('title', trans_choice('general.bills', 1) . ': ' . $bill->bill_number)

@section('content')
    @if (($recurring = $bill->recurring) && ($next = $recurring->next()))
        <div class="callout callout-info">
            <h4>{{ trans('recurring.recurring') }}</h4>

            <p>{{ trans('recurring.message', [
                    'type' => mb_strtolower(trans_choice('general.bills', 1)),
                    'date' => $next->format($date_format)
                ]) }}
            </p>
        </div>
    @endif

    <div class="box box-success">
        <div class="bill">
            <div id="badge">
                <div class="arrow-up"></div>
                <div class="label {{ $bill->status->label }}">{{ trans('bills.status.' . $bill->status->code) }}</div>
                <div class="arrow-right"></div>
            </div>

            <div class="row invoice-header">
                <div class="col-xs-7">
                    @if (isset($bill->vendor->logo) && !empty($bill->vendor->logo->id))
                        <img src="{{ Storage::url($bill->vendor->logo->id) }}" class="invoice-logo" />
                    @else
                        <img src="{{ asset('public/img/company.png') }}" class="invoice-logo" />
                    @endif
                </div>
                <div class="col-xs-5 invoice-company">
                    <address>
                        <strong>{{ setting('general.company_name') }}</strong><br>
                        {!! nl2br(setting('general.company_address')) !!}<br>
                        @if (setting('general.company_tax_number'))
                            {{ trans('general.tax_number') }}: {{ setting('general.company_tax_number') }}<br>
                        @endif
                        <br>
                        @if (setting('general.company_phone'))
                            {{ setting('general.company_phone') }}<br>
                        @endif
                        {{ setting('general.company_email') }}
                    </address>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-7">
                    {{ trans('bills.bill_from') }}
                    <address>
                        <strong>{{ $bill->vendor_name }}</strong><br>
                        {!! nl2br($bill->vendor_address) !!}<br>
                        @if ($bill->vendor_tax_number)
                        {{ trans('general.tax_number') }}: {{ $bill->vendor_tax_number }}<br>
                        @endif
                        <br>
                        @if ($bill->vendor_phone)
                        {{ $bill->vendor_phone }}<br>
                        @endif
                        {{ $bill->vendor_email }}
                    </address>
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table no-border">
                            <tbody>
                            <tr>
                                <th>{{ trans('bills.bill_number') }}:</th>
                                <td class="text-right">{{ $bill->bill_number }}</td>
                            </tr>
                            @if ($bill->order_number)
                            <tr>
                                <th>{{ trans('bills.order_number') }}:</th>
                                <td class="text-right">{{ $bill->order_number }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>{{ trans('bills.bill_date') }}:</th>
                                <td class="text-right">{{ Date::parse($bill->billed_at)->format($date_format) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('bills.payment_due') }}:</th>
                                <td class="text-right">{{ Date::parse($bill->due_at)->format($date_format) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>{{ trans_choice('general.items', 1) }}</th>
                                <th class="text-center">{{ trans('bills.quantity') }}</th>
                                <th class="text-right">{{ trans('bills.price') }}</th>
                                <th class="text-right">{{ trans('bills.total') }}</th>
                            </tr>
                            @foreach($bill->items as $item)
                            <tr>
                                <td>
                                    {{ $item->name }}
                                    @if ($item->sku)
                                        <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">@money($item->price, $bill->currency_code, true)</td>
                                <td class="text-right">@money($item->total, $bill->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-7">
                    @if ($bill->notes)
                        <p class="lead">{{ trans_choice('general.notes', 2) }}:</p>

                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            {{ $bill->notes }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach ($bill->totals as $total)
                                @if ($total->code != 'total')
                                    <tr>
                                        <th>{{ trans($total->title) }}:</th>
                                        <td class="text-right">@money($total->amount, $bill->currency_code, true)</td>
                                    </tr>
                                @else
                                    @if ($bill->paid)
                                        <tr class="text-success">
                                            <th>{{ trans('invoices.paid') }}:</th>
                                            <td class="text-right">- @money($bill->paid, $bill->currency_code, true)</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans($total->name) }}:</th>
                                        <td class="text-right">@money($total->amount - $bill->paid, $bill->currency_code, true)</td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box-footer row no-print">
                <div class="col-xs-12">
                    <a href="{{ url('expenses/bills/' . $bill->id . '/edit') }}" class="btn btn-default">
                        <i class="fa fa-pencil-square-o"></i>&nbsp; {{ trans('general.edit') }}
                    </a>
                    <a href="{{ url('expenses/bills/' . $bill->id . '/print') }}" target="_blank" class="btn btn-success">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-circle-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                        <ul class="dropdown-menu" role="menu">
                            @if($bill->status->code != 'paid')
                            <li><a href="#" id="button-payment">{{ trans('bills.add_payment') }}</a></li>
                            @permission('update-expenses-bills')
                            @if($bill->bill_status_code == 'draft')
                            <li><a href="{{ url('expenses/bills/' . $bill->id . '/received') }}">{{ trans('bills.mark_received') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="disabled"><span class="text-disabled">{{ trans('bills.mark_received') }}</span></a></li>
                            @endif
                            @endpermission
                            <li class="divider"></li>
                            @endif
                            <li><a href="{{ url('expenses/bills/' . $bill->id . '/pdf') }}">{{ trans('bills.download_pdf') }}</a></li>
                            <li class="divider"></li>
                            @permission('delete-expenses-bills')
                            <li>{!! Form::deleteLink($bill, 'expenses/bills') !!}</li>
                            @endpermission
                        </ul>
                    </div>

                    @if($bill->attachment)
                        <span class="attachment">
                            <a href="{{ url('uploads/' . $bill->attachment->id . '/download') }}">
                                <span id="download-attachment" class="text-primary">
                                    <i class="fa fa-file-{{ $bill->attachment->aggregate_type }}-o"></i> {{ $bill->attachment->basename }}
                                </span>
                            </a>
                            {!! Form::open([
                                'id' => 'attachment-' . $bill->attachment->id,
                                'method' => 'DELETE',
                                'url' => [url('uploads/' . $bill->attachment->id)],
                                'style' => 'display:inline'
                            ]) !!}
                            <a id="remove-attachment" href="javascript:void();">
                                <span class="text-danger"><i class="fa fa fa-times"></i></span>
                            </a>
                            {!! Form::close() !!}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('bills.histories') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('general.date') }}</th>
                                <th>{{ trans_choice('general.statuses', 1) }}</th>
                                <th>{{ trans('general.description') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bill->histories as $history)
                                <tr>
                                    <td>{{ Date::parse($history->created_at)->format($date_format) }}</td>
                                    <td>{{ $history->status->name }}</td>
                                    <td>{{ $history->description }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-6">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('bills.payments') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ trans('general.date') }}</th>
                                <th>{{ trans('general.amount') }}</th>
                                <th>{{ trans_choice('general.accounts', 1) }}</th>
                                <th style="width: 15%;">{{ trans('general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bill->payments as $payment)
                                <tr>
                                    <td>{{ Date::parse($payment->paid_at)->format($date_format) }}</td>
                                    <td>@money($payment->amount, $payment->currency_code, true)</td>
                                    <td>{{ $payment->account->name }}</td>
                                    <td>
                                        <a href="{{ url('expenses/bills/' . $payment->id) }}" class="btn btn-info btn-xs hidden"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                        <a href="{{ url('expenses/bills/' . $payment->id . '/edit') }}" class="btn btn-primary btn-xs  hidden"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                                        {!! Form::open([
                                            'id' => 'bill-payment-' . $payment->id,
                                            'method' => 'DELETE',
                                            'url' => ['expenses/bills/payment', $payment->id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'btn btn-danger btn-xs',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmDelete("' . '#bill-payment-' . $payment->id . '", "' . trans_choice('general.payments', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . Date::parse($payment->paid_at)->format($date_format) . ' - ' . money($payment->amount, $payment->currency_code, true) . ' - ' . $payment->account->name . '</strong>', 'type' => strtolower(trans_choice('general.revenues', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
                                        )) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '#button-email', function (e) {
                $('#email-modal').remove();

                var html = '<div class="modal fade" id="email-modal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel">';
                html += '   <div class="modal-dialog" role="document">';
                html += '       <div class="modal-content">';
                html += '           <div class="modal-header">';
                html += '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '               <h4 class="modal-title" id="emailModalLabel">Overflowing text</h4>';
                html += '           </div>';
                html += '           <div class="modal-body">';
                html += '               {{ trans('general.na') }}';
                html += '           </div>';
                html += '           <div class="modal-footer">';
                html += '               <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.cancel') }}</button>';
                html += '               <button type="button" class="btn btn-success">Save changes</button>';
                html += '           </div>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';

                $('body').append(html);

                $('#email-modal').modal('show');
            });

            @if($bill->attachment)
            $(document).on('click', '#remove-attachment', function (e) {
                confirmDelete("#attachment-{!! $bill->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $bill->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endif
        });

        $(document).on('click', '#button-payment', function (e) {
            $('#modal-add-payment').remove();

            $.ajax({
                url: '{{ url("modals/bills/" . $bill->id . "/payment/create") }}',
                type: 'GET',
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
        });
    </script>
@endpush
