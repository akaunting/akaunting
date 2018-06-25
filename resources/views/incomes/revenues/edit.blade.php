@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.revenues', 1)]))

@section('content')
    @if (($recurring = $revenue->recurring) && ($next = $recurring->next()))
        <div class="callout callout-info">
            <h4>{{ trans('recurring.recurring') }}</h4>

            <p>{{ trans('recurring.message', [
                    'type' => mb_strtolower(trans_choice('general.revenues', 1)),
                    'date' => $next->format($date_format)
                ]) }}
            </p>
        </div>
    @endif

    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($revenue, [
            'method' => 'PATCH',
            'files' => true,
            'url' => ['incomes/revenues', $revenue->id],
            'role' => 'form'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('paid_at', trans('general.date'), 'calendar', ['id' => 'paid_at', 'class' => 'form-control', 'required' => 'required', 'data-inputmask' => '\'alias\': \'yyyy-mm-dd\'', 'data-mask' => ''], Date::parse($revenue->paid_at)->toDateString()) }}

            {{ Form::textGroup('amount', trans('general.amount'), 'money', ['required' => 'required', 'autofocus' => 'autofocus']) }}

            <div class="form-group col-md-6 form-small">
                {!! Form::label('account_id', trans_choice('general.accounts', 1), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-university"></i></div>
                    {!! Form::select('account_id', $accounts, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)])])) !!}
                    <div class="input-group-append">
                        {!! Form::text('currency', $account_currency_code, ['id' => 'currency', 'class' => 'form-control', 'required' => 'required', 'disabled' => 'disabled']) !!}
                        {!! Form::hidden('currency_code', $account_currency_code, ['id' => 'currency_code', 'class' => 'form-control', 'required' => 'required']) !!}
                        {!! Form::hidden('currency_rate', null, ['id' => 'currency_rate']) !!}
                    </div>
                </div>
            </div>

            {{ Form::selectGroup('customer_id', trans_choice('general.customers', 1), 'user', $customers, null, []) }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder-open-o', $categories) }}

            {{ Form::recurring('edit', $revenue) }}

            {{ Form::selectGroup('payment_method', trans_choice('general.payment_methods', 1), 'credit-card', $payment_methods) }}

            {{ Form::textGroup('reference', trans('general.reference'), 'file-text-o',[]) }}

            {{ Form::fileGroup('attachment', trans('general.attachment')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-incomes-revenues')
        <div class="box-footer">
            {{ Form::saveButtons('incomes/revenues') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').trigger('change');

            //Date picker
            $('#paid_at').datepicker({
                format: 'yyyy-mm-dd',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $("#account_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.accounts', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $("#customer_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.customers', 1)]) }}"
            });

            $("#payment_method").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.payment_methods', 1)]) }}"
            });

            $('#attachment').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($revenue->attachment)
                placeholder : '<?php echo $revenue->attachment->basename; ?>'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($revenue->attachment)
                attachment_html  = '<span class="attachment">';
                attachment_html += '    <a href="{{ url('uploads/' . $revenue->attachment->id . '/download') }}">';
                attachment_html += '        <span id="download-attachment" class="text-primary">';
                attachment_html += '            <i class="fa fa-file-{{ $revenue->attachment->aggregate_type }}-o"></i> {{ $revenue->attachment->basename }}';
                attachment_html += '        </span>';
                attachment_html += '    </a>';
                attachment_html += '    {!! Form::open(['id' => 'attachment-' . $revenue->attachment->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $revenue->attachment->id)], 'style' => 'display:inline']) !!}';
                attachment_html += '    <a id="remove-attachment" href="javascript:void();">';
                attachment_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
                attachment_html += '    </a>';
                attachment_html += '    {!! Form::close() !!}';
                attachment_html += '</span>';

                $('.fancy-file .fake-file').append(attachment_html);

                $(document).on('click', '#remove-attachment', function (e) {
                    confirmDelete("#attachment-{!! $revenue->attachment->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $revenue->attachment->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
                });
            @endif
        });

        $(document).on('change', '#account_id', function (e) {
            $.ajax({
                url: '{{ url("banking/accounts/currency") }}',
                type: 'GET',
                dataType: 'JSON',
                data: 'account_id=' + $(this).val(),
                success: function(data) {
                    $('#currency').val(data.currency_code);

                    $('#currency_code').val(data.currency_code);
                    $('#currency_rate').val(data.currency_rate);
                }
            });
        });
    </script>
@endpush
