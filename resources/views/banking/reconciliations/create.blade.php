@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.reconciliations', 1)]))

@section('content')
    <div class="row">
        {!! Form::open(['url' => 'banking/reconciliations/create', 'role' => 'form', 'method' => 'GET']) !!}

        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-body">
                    {{ Form::textGroup('started_at', trans('reconciliations.start_date'), 'calendar',['id' => 'started_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], request('started_at'), 'col-md-3') }}

                    {{ Form::textGroup('ended_at', trans('reconciliations.end_date'), 'calendar',['id' => 'ended_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => '', 'autocomplete' => 'off'], request('started_at'), 'col-md-3') }}

                    {{ Form::textGroup('closing_balance', trans('reconciliations.closing_balance'), 'money', ['required' => 'required', 'autofocus' => 'autofocus'], '0', 'col-md-2') }}

                    {{ Form::selectGroup('account_id', trans_choice('general.accounts', 1), 'university', $accounts, request('account_id', setting('general.default_account')), ['required' => 'required'], 'col-md-2') }}

                    <div class="form-group col-md-2">
                        <div class="input-group" style="margin-top: 25px;">
                            {!! Form::button('<span class="fa fa-list"></span> &nbsp;' . trans('reconciliations.list_transactions'), ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        {!! Form::close() !!}

        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ trans_choice('general.transactions', 2) }}</h3>
                </div>
                <div class="box-body">
                    {!! Form::open(['url' => 'banking/reconciliations', 'role' => 'form', 'class' => 'form-loading-button', 'id' => 'form-reconciliations']) !!}

                    {{ Form::hidden('account_id', $account->id) }}
                    {{ Form::hidden('currency_code', $currency->code, ['id' => 'currency_code']) }}
                    {{ Form::hidden('opening_balance', $opening_balance, ['id' => 'opening_balance']) }}
                    {{ Form::hidden('closing_balance', request('closing_balance', '0'), ['id' => 'closing_balance']) }}
                    {{ Form::hidden('started_at', request('started_at')) }}
                    {{ Form::hidden('ended_at', request('ended_at')) }}
                    {{ Form::hidden('reconcile', '0', ['id' => 'hidden-reconcile']) }}
                    <div class="table table-responsive">
                        <table class="table table-striped table-hover" id="tbl-transactions">
                            <thead>
                            <tr>
                                <th class="col-md-2">{{ trans('general.date') }}</th>
                                <th class="col-md-3">{{ trans('general.description') }}</th>
                                <th class="col-md-2">{{ trans_choice('general.contacts', 1) }}</th>
                                <th class="col-md-2 text-right">{{ trans('reconciliations.deposit') }}</th>
                                <th class="col-md-2 text-right">{{ trans('reconciliations.withdrawal') }}</th>
                                <th class="col-md-1 text-right">{{ trans('general.clear') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $item)
                                <tr>
                                    <td>{{ Date::parse($item->paid_at)->format($date_format) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>@if (!empty($item->contact)) {{ $item->contact->name }} @else {{ trans('general.na') }}@endif</td>
                                    @if (($item->model == 'App\Models\Income\InvoicePayment') || ($item->model == 'App\Models\Income\Revenue'))
                                    <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                                    <td>&nbsp;</td>
                                    @else
                                    <td>&nbsp;</td>
                                    <td class="text-right">@money($item->amount, $item->currency_code, true)</td>
                                    @endif
                                    <td class="text-right">{{ Form::checkbox('transactions['. $item->id . '_'. $item->model . ']', $item->amount, $item->reconciled) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if ($transactions->count())
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="text-right">{{ trans('reconciliations.closing_balance') }}:</th>
                                    <td id="closing-balance" class="col-md-1 text-right">@money(request('closing_balance', '0'), $account->currency_code, true)</td>
                                </tr>
                                <tr>
                                    <th class="text-right">{{ trans('reconciliations.cleared_amount') }}:</th>
                                    <td id="cleared-amount" class="col-md-1 text-right">@money('0', $account->currency_code, true)</td>
                                </tr>
                                <tr>
                                    <th class="text-right">{{ trans('general.difference') }}:</th>
                                    <td id="difference" class="col-md-1 text-right">@money('0', $account->currency_code, true)</td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                <div class="box-footer">
                    @if ($transactions->count())
                    <div class="form-group no-margin">
                        {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'submit', 'class' => 'btn btn-default button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                        {!! Form::button('<span class="fa fa-check"></span> &nbsp;' . trans('reconciliations.reconcile'), ['type' => 'button', 'id' => 'button-reconcile', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading'), 'disabled' => 'disabled']) !!}
                        <a href="{{ route('reconciliations.index') }}" class="btn btn-default"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</a>
                    </div>
                    @else
                    {{ trans('general.no_records') }}
                    @endif
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @if (language()->getShortCode() != 'en')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    @endif
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#closing_balance").maskMoney({
                thousands : '{{ $currency->thousands_separator }}',
                decimal : '{{ $currency->decimal_mark }}',
                precision : {{ $currency->precision }},
                allowZero : true,
                allowNegative: true,
                @if($currency->symbol_first)
                prefix : '{{ $currency->symbol }}'
                @else
                suffix : '{{ $currency->symbol }}'
                @endif
            });

            $("#account_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
            });

            //Date picker
            $('#started_at').datepicker({
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $('#ended_at').datepicker({
                format: 'yyyy-mm-dd',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });
        });

        $(document).on('change', '#account_id', function (e) {
            $.ajax({
                url: '{{ url("banking/accounts/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'account_id=' + $(this).val(),
                success: function(data) {
                    $('#currency_code').val(data.currency_code);

                    amount = $('#closing_balance').maskMoney('unmasked')[0];

                    $("#closing_balance").maskMoney({
                        thousands : data.thousands_separator,
                        decimal : data.decimal_mark,
                        precision : data.precision,
                        allowZero : true,
                        prefix : (data.symbol_first) ? data.symbol : '',
                        suffix : (data.symbol_first) ? '' : data.symbol
                    });

                    $('#closing_balance').val(amount);

                    $('#closing_balance').trigger('focus');
                }
            });
        });

        $(document).ready(function(){
            $('#tbl-transactions input[type="checkbox"]').trigger('change');
        });

        $(document).on('change', '#tbl-transactions input[type="checkbox"]', function (e) {
            amount = $('#closing_balance').maskMoney('unmasked')[0];

            $('#form-reconciliations #closing_balance').val(amount);

            $.ajax({
                url: '{{ url("banking/reconciliations/calculate") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $('#form-reconciliations').serialize(),
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(data) {
                    if (data) {
                        if (data.difference_raw != 0)  {
                            $('#button-reconcile').attr('disabled','disabled');
                            $('#difference').css('background-color', '#f2dede');
                        } else {
                            $('#button-reconcile').removeAttr('disabled');
                            $('#difference').css('background-color', '#d0e9c6');
                        }

                        $('#closing-balance').html(data.closing_balance);
                        $('#cleared-amount').html(data.cleared_amount);
                        $('#difference').html(data.difference);
                    }
                }
            });
        });

        $(document).on('click', '#button-reconcile', function (e) {
            $('#hidden-reconcile').val(1);

            $('#form-reconciliations').submit();
        });
    </script>
@endpush
