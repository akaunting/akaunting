@extends('layouts.admin')

@section('title', trans_choice('general.invoices', 1) . ': ' . $invoice->invoice_number)

@section('content')
    @if (($recurring = $invoice->recurring) && ($next = $recurring->next()))
        <div class="callout callout-info">
            <h4>{{ trans('recurring.recurring') }}</h4>

            <p>{{ trans('recurring.message', [
                    'type' => mb_strtolower(trans_choice('general.invoices', 1)),
                    'date' => $next->format($date_format)
                ]) }}
            </p>
        </div>
    @endif

    <div class="box box-success">
        <section class="invoice">
            <span class="badge bg-aqua">{{ $invoice->status->name }}</span>

            <div class="row invoice-header">
                <div class="col-xs-7">
                    @if (setting('general.invoice_logo'))
                        <img src="{{ Storage::url(setting('general.invoice_logo')) }}" class="invoice-logo" />
                    @elseif (setting('general.company_logo'))
                        <img src="{{ Storage::url(setting('general.company_logo')) }}" class="invoice-logo" />
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
                    {{ trans('invoices.bill_to') }}
                    <address>
                        <strong>{{ $invoice->customer_name }}</strong><br>
                        {!! nl2br($invoice->customer_address) !!}<br>
                        @if ($invoice->customer_tax_number)
                        {{ trans('general.tax_number') }}: {{ $invoice->customer_tax_number }}<br>
                        @endif
                        <br>
                        @if ($invoice->customer_phone)
                        {{ $invoice->customer_phone }}<br>
                        @endif
                        {{ $invoice->customer_email }}
                    </address>
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table no-border">
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
                                    <td class="text-right">{{ Date::parse($invoice->invoiced_at)->format($date_format) }}</td>
                                </tr>
                                <tr>
                                    <th>{{ trans('invoices.payment_due') }}:</th>
                                    <td class="text-right">{{ Date::parse($invoice->due_at)->format($date_format) }}</td>
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
                                <th class="text-center">{{ trans('invoices.quantity') }}</th>
                                <th class="text-right">{{ trans('invoices.price') }}</th>
                                <th class="text-right">{{ trans('invoices.total') }}</th>
                            </tr>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>
                                    {{ $item->name }}
                                    @if ($item->sku)
                                        <br><small>{{ trans('items.sku') }}: {{ $item->sku }}</small>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right">@money($item->price, $invoice->currency_code, true)</td>
                                <td class="text-right">@money($item->total, $invoice->currency_code, true)</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-7">
                @if ($invoice->notes)
                    <p class="lead">{{ trans_choice('general.notes', 2) }}</p>

                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{ $invoice->notes }}
                    </p>
                @endif
                </div>
                <div class="col-xs-5">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                @foreach ($invoice->totals as $total)
                                @if ($total->code != 'total')
                                    <tr>
                                        <th>{{ trans($total->title) }}:</th>
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
                                        <th>{{ trans($total->name) }}:</th>
                                        <td class="text-right">@money($total->amount - $invoice->paid, $invoice->currency_code, true)</td>
                                    </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box-footer row no-print">
                <div class="col-md-12">
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/edit') }}" class="btn btn-primary">
                        <i class="fa fa-pencil-square-o"></i>&nbsp; {{ trans('general.edit') }}
                    </a>
                    <a href="{{ url('incomes/invoices/' . $invoice->id . '/print') }}" target="_blank" class="btn btn-default">
                        <i class="fa fa-print"></i>&nbsp; {{ trans('general.print') }}
                    </a>
                    <div class="btn-group dropup">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-chevron-circle-up"></i>&nbsp; {{ trans('general.more_actions') }}</button>
                        <ul class="dropdown-menu" role="menu">
                            @if($invoice->status->code != 'paid')
                            @permission('update-incomes-invoices')
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/pay') }}">{{ trans('invoices.mark_paid') }}</a></li>
                            @endpermission
                            @if(empty($invoice->payments()->count()) || (!empty($invoice->payments()->count()) && $invoice->payments()->paid() != $invoice->amount))
                            <li><a href="#" id="button-payment">{{ trans('invoices.add_payment') }}</a></li>
                            @endif
                            <li class="divider"></li>
                            @endif
                            @permission('update-incomes-invoices')
                            @if($invoice->invoice_status_code == 'draft')
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/sent') }}">{{ trans('invoices.mark_sent') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="disabled"><span class="text-disabled">{{ trans('invoices.mark_sent') }}</span></a></li>
                            @endif
                            @endpermission
                            @if($invoice->customer_email)
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/email') }}">{{ trans('invoices.send_mail') }}</a></li>
                            @else
                            <li><a href="javascript:void(0);" class="green-tooltip disabled" data-toggle="tooltip" data-placement="right" title="{{ trans('invoices.messages.email_required') }}"><span class="text-disabled">{{ trans('invoices.send_mail') }}</span></a></li>
                            @endif
                            <li class="divider"></li>
                            <li><a href="{{ url('incomes/invoices/' . $invoice->id . '/pdf') }}">{{ trans('invoices.download_pdf') }}</a></li>
                            <li class="divider"></li>
                            @permission('delete-incomes-invoices')
                            <li>{!! Form::deleteLink($invoice, 'incomes/invoices') !!}</li>
                            @endpermission
                        </ul>
                    </div>

                    @if($invoice->attachment)
                        <span class="attachment">
                            <a href="{{ url('uploads/' . $invoice->attachment->id . '/download') }}">
                                <span id="download-attachment" class="text-primary">
                                    <i class="fa fa-file-{{ $invoice->attachment->aggregate_type }}-o"></i> {{ $invoice->attachment->basename }}
                                </span>
                            </a>
                            {!! Form::open([
                                'id' => 'attachment-' . $invoice->attachment->id,
                                'method' => 'DELETE',
                                'url' => [url('uploads/' . $invoice->attachment->id)],
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
        </section>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans('invoices.histories') }}</h3>

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
                            @foreach($invoice->histories as $history)
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
                    <h3 class="box-title">{{ trans('invoices.payments') }}</h3>

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
                            @foreach($invoice->payments as $payment)
                                <tr>
                                    <td>{{ Date::parse($payment->paid_at)->format($date_format) }}</td>
                                    <td>@money($payment->amount, $payment->currency_code, true)</td>
                                    <td>{{ $payment->account->name }}</td>
                                    <td>
                                        <a href="{{ url('incomes/invoices/' . $payment->id . '') }}" class="btn btn-info btn-xs hidden"><i class="fa fa-eye" aria-hidden="true"></i> {{ trans('general.show') }}</a>
                                        <a href="{{ url('incomes/revenues/' . $payment->id . '/edit') }}" class="btn btn-primary btn-xs  hidden"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ trans('general.edit') }}</a>
                                        {!! Form::open([
                                            'id' => 'invoice-payment-' . $payment->id,
                                            'method' => 'DELETE',
                                            'url' => ['incomes/invoices/payment', $payment->id],
                                            'style' => 'display:inline'
                                        ]) !!}
                                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ' . trans('general.delete'), array(
                                            'type'    => 'button',
                                            'class'   => 'btn btn-danger btn-xs',
                                            'title'   => trans('general.delete'),
                                            'onclick' => 'confirmDelete("' . '#invoice-payment-' . $payment->id . '", "' . trans_choice('general.payments', 2) . '", "' . trans('general.delete_confirm', ['name' => '<strong>' . Date::parse($payment->paid_at)->format($date_format) . ' - ' . money($payment->amount, $payment->currency_code, true) . ' - ' . $payment->account->name . '</strong>', 'type' => strtolower(trans_choice('general.revenues', 1))]) . '", "' . trans('general.cancel') . '", "' . trans('general.delete') . '")'
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
            $(document).on('click', '#button-payment', function (e) {
                $('#payment-modal').remove();

                var html = '';

                html += '<div class="modal fade" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">';
                html += '   <div class="modal-dialog" role="document">';
                html += '       <div class="modal-content box box-success">';
                html += '           <div class="modal-header">';
                html += '               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                html += '               <h4 class="modal-title" id="paymentModalLabel">{{ trans('invoices.add_payment') }}</h4>';
                html += '           </div>';
                html += '           <div class="modal-body box-body">';
                html += '               <div class="modal-message"></div>';
                html += '               <div class="form-group col-md-6 required">';
                html += '                   {!! Form::label('paid_at', trans('general.date'), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-calendar"></i></div>';
                html += '                       {!! Form::text('paid_at', \Carbon\Carbon::now()->toDateString(), ['id' => 'paid_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '']) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               <div class="form-group col-md-6 required">';
                html += '                   {!! Form::label('amount', trans('general.amount'), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-money"></i></div>';
                html += '                       {!! Form::text('amount', $invoice->grand_total, ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.enter', ['field' => trans('general.amount')])]) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               <div class="form-group col-md-6 required">';
                html += '                   {!! Form::label('account_id', trans_choice('general.accounts', 1), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-university"></i></div>';
                html += '                       {!! Form::select('account_id', $accounts, setting('general.default_account'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)])]) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               <div class="form-group col-md-6 required">';
                html += '                   {!! Form::label('currency_code', trans_choice('general.currencies', 1), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-exchange"></i></div>';
                html += '                       {!! Form::text('currency', $currencies[$account_currency_code], ['id' => 'currency', 'class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}';
                html += '                       {!! Form::hidden('currency_code', $account_currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               <div class="form-group col-md-12">';
                html += '                   {!! Form::label('description', trans('general.description'), ['class' => 'control-label']) !!}';
                html += '                   {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3', 'placeholder' => trans('general.form.enter', ['field' => trans('general.description')])]) !!}';
                html += '               </div>';
                html += '               <div class="form-group col-md-6 required">';
                html += '                   {!! Form::label('payment_method', trans_choice('general.payment_methods', 1), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-folder-open-o"></i></div>';
                html += '                       {!! Form::select('payment_method', $payment_methods, setting('general.default_payment_method'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)])]) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               <div class="form-group col-md-6">';
                html += '                   {!! Form::label('reference', trans('general.reference'), ['class' => 'control-label']) !!}';
                html += '                   <div class="input-group">';
                html += '                       <div class="input-group-addon"><i class="fa fa-file-text-o"></i></div>';
                html += '                       {!! Form::text('reference', null, ['class' => 'form-control', 'placeholder' => trans('general.form.enter', ['field' => trans('general.reference')])]) !!}';
                html += '                   </div>';
                html += '               </div>';
                html += '               {!! Form::hidden('invoice_id', $invoice->id, ['id' => 'invoice_id', 'class' => 'form-control', 'required' => 'required']) !!}';
                html += '           </div>';
                html += '           <div class="modal-footer" style="text-align: left;">';
                html += '               <button type="button" onclick="addPayment();" class="btn btn-success">{{ trans('general.save') }}</button>';
                html += '               <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('general.cancel') }}</button>';
                html += '           </div>';
                html += '       </div>';
                html += '   </div>';
                html += '</div>';

                $('body').append(html);

                $('#paid_at').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });

                $("#account_id").select2({
                    placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
                });

                $("#payment_method").select2({
                    placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)]) }}"
                });

                $('#payment-modal').modal('show');
            });

            $(document).on('change', '#account_id', function (e) {
                $.ajax({
                    url: '{{ url("settings/currencies/currency") }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'account_id=' + $(this).val(),
                    success: function(data) {
                        $('#currency').val(data.currency_name);
                        $('#currency_code').val(data.currency_code);
                    }
                });
            });

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
                html += '              {{ trans('general.na') }}';
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
            @if($invoice->attachment)
            $(document).on('click', '#remove-attachment', function (e) {
                confirmDelete("#attachment-{!! $invoice->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $invoice->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endif
        });

        function addPayment() {
            $('.help-block').remove();

            $.ajax({
                url: '{{ url("incomes/invoices/payment") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#payment-modal input[type=\'text\'], #payment-modal input[type=\'hidden\'], #payment-modal textarea, #payment-modal select'),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                beforeSend: function() {
                    $('#payment-modal .modal-content').append('<div id="loading" class="text-center"><i class="fa fa-spinner fa-spin fa-5x checkout-spin"></i></div>');
                },
                complete: function() {
                    $('#loading').remove();
                },
                success: function(json) {
                    if (json['error']) {
                        $('#payment-modal .modal-message').append('<div class="alert alert-danger">' + json['message'] + '</div>');
                        $('div.alert-danger').delay(3000).fadeOut(350);
                    }

                    if (json['success']) {
                        $('#payment-modal .modal-message').before('<div class="alert alert-success">' + json['message'] + '</div>');
                        $('div.alert-success').delay(3000).fadeOut(350);

                        setTimeout(function(){
                            $("#payment-modal").modal('hide');

                            location.reload();
                        }, 3000);
                    }
                },
                error: function(data){
                    var errors = data.responseJSON;

                    if (typeof errors !== 'undefined') {
                        if (errors.paid_at) {
                            $('#payment-modal #paid_at').parent().after('<p class="help-block">' + errors.paid_at + '</p>');
                        }

                        if (errors.amount) {
                            $('#payment-modal #amount').parent().after('<p class="help-block">' + errors.amount + '</p>');
                        }

                        if (errors.account_id) {
                            $('#payment-modal #account_id').parent().after('<p class="help-block">' + errors.account_id + '</p>');
                        }

                        if (errors.currency_code) {
                            $('#payment-modal #currency_code').parent().after('<p class="help-block">' + errors.currency_code + '</p>');
                        }

                        if (errors.category_id) {
                            $('#payment-modal #category_id').parent().after('<p class="help-block">' + errors.category_id + '</p>');
                        }

                        if (errors.payment_method) {
                            $('#payment-modal #payment_method').parent().after('<p class="help-block">' + errors.payment_method + '</p>');
                        }
                    }
                }
            });
        }
    </script>
@endpush
