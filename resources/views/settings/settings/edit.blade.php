@extends('layouts.admin')

@section('title', trans('general.general'))

@section('content')
    <!-- Default box -->
    <div class="row">
        {!! Form::model($setting, [
            'method' => 'PATCH',
            'url' => ['settings/settings'],
            'class' => 'setting-form form-loading-button',
            'files' => true,
            'role' => 'form',
        ]) !!}

        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#company" data-toggle="tab" aria-expanded="true">{{ trans_choice('general.companies', 1) }}</a></li>
                    <li class=""><a href="#localisation" data-toggle="tab" aria-expanded="false">{{ trans('settings.localisation.tab') }}</a></li>
                    <li class=""><a href="#invoice" data-toggle="tab" aria-expanded="false">{{ trans('settings.invoice.tab') }}</a></li>
                    <li class=""><a href="#default" data-toggle="tab" aria-expanded="false">{{ trans('settings.default.tab') }}</a></li>
                    <li class=""><a href="#email" data-toggle="tab" aria-expanded="false">{{ trans('general.email') }}</a></li>
                    <li class=""><a href="#scheduling" data-toggle="tab" aria-expanded="false">{{ trans('settings.scheduling.tab') }}</a></li>
                    <li class=""><a href="#appearance" data-toggle="tab" aria-expanded="false">{{ trans('settings.appearance.tab') }}</a></li>
                    <li class=""><a href="#system" data-toggle="tab" aria-expanded="false">{{ trans('settings.system.tab') }}</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane tab-margin active" id="company">
                        {{ Form::textGroup('company_name', trans('settings.company.name'), 'id-card-o') }}

                        {{ Form::textGroup('company_email', trans('settings.company.email'), 'envelope') }}

                        {{ Form::textGroup('company_tax_number', trans('general.tax_number'), 'percent', []) }}

                        {{ Form::textGroup('company_phone', trans('settings.company.phone'), 'phone', []) }}

                        {{ Form::textareaGroup('company_address', trans('settings.company.address')) }}

                        {{ Form::fileGroup('company_logo', trans('settings.company.logo')) }}

                        {!! Form::hidden('wizard', null, ['id' => 'wizard']) !!}
                    </div>

                    <div class="tab-pane tab-margin" id="localisation">
                        {{ Form::textGroup('financial_start', trans('settings.localisation.financial_start'), 'calendar-check-o', ['id' => 'financial_start', 'class' => 'form-control', 'data-inputmask' => '\'alias\': \'dd-mm\'', 'data-mask' => '', 'autocomplete' => 'off'], $setting['financial_start']) }}

                        {{ Form::selectGroup('timezone', trans('settings.localisation.timezone'), 'globe', $timezones, null, []) }}

                        {{ Form::selectGroup('date_format', trans('settings.localisation.date.format'), 'calendar', $date_formats, null, ['autocomplete' => 'off']) }}

                        {{ Form::selectGroup('date_separator', trans('settings.localisation.date.separator'), 'minus', $date_separators, null, []) }}

                        {{ Form::selectGroup('percent_position', trans('settings.localisation.percent.title'), 'percent', $percent_positions, null, []) }}
                    </div>

                    <div class="tab-pane tab-margin" id="invoice">
                        {{ Form::textGroup('invoice_number_prefix', trans('settings.invoice.prefix'), 'font', []) }}

                        {{ Form::textGroup('invoice_number_digit', trans('settings.invoice.digit'), 'text-width', []) }}

                        {{ Form::textGroup('invoice_number_next', trans('settings.invoice.next'), 'chevron-right', []) }}

                        {{ Form::invoice_text('invoice_item', trans('settings.invoice.item_name'), 'font', $item_names, null, [], 'invoice_item_input', null) }}

                        {{ Form::invoice_text('invoice_price', trans('settings.invoice.price_name'), 'font', $price_names, null, [], 'invoice_price_input', null) }}

                        {{ Form::invoice_text('invoice_quantity', trans('settings.invoice.quantity_name'), 'font', $quantity_names, null, [], 'invoice_quantity_input', null) }}

                        {{ Form::fileGroup('invoice_logo', trans('settings.invoice.logo')) }}
                    </div>

                    <div class="tab-pane tab-margin" id="default">
                        {{ Form::selectGroup('default_account', trans('settings.default.account'), 'university', $accounts, null, []) }}

                        {{ Form::selectGroup('default_currency', trans('settings.default.currency'), 'exchange', $currencies, null, []) }}

                        {{ Form::selectGroup('default_tax', trans('settings.default.tax'), 'percent', $taxes, null, []) }}

                        {{ Form::selectGroup('default_payment_method', trans('settings.default.payment'), 'credit-card', $payment_methods, setting('general.default_payment_method'), []) }}

                        {{ Form::selectGroup('default_locale', trans('settings.default.language'), 'flag', language()->allowed(), null, []) }}
                    </div>

                    <div class="tab-pane tab-margin" id="email">
                        {{ Form::selectGroup('email_protocol', trans('settings.email.protocol'), 'share', $email_protocols, null, []) }}

                        {{ Form::textGroup('email_sendmail_path', trans('settings.email.sendmail_path'), 'road', []) }}

                        {{ Form::textGroup('email_smtp_host', trans('settings.email.smtp.host'), 'paper-plane-o', []) }}

                        {{ Form::textGroup('email_smtp_port', trans('settings.email.smtp.port'), 'paper-plane-o', []) }}

                        {{ Form::textGroup('email_smtp_username', trans('settings.email.smtp.username'), 'paper-plane-o', []) }}

                        {{ Form::textGroup('email_smtp_password', trans('settings.email.smtp.password'), 'paper-plane-o', ['type' => 'password']) }}

                        {{ Form::selectGroup('email_smtp_encryption', trans('settings.email.smtp.encryption'), 'paper-plane-o', ['' => trans('settings.email.smtp.none'), 'ssl' => 'SSL', 'tls' => 'TLS'], null, []) }}
                    </div>

                    <div class="tab-pane tab-margin" id="scheduling">
                        {{ Form::radioGroup('send_invoice_reminder', trans('settings.scheduling.send_invoice')) }}

                        {{ Form::textGroup('schedule_invoice_days', trans('settings.scheduling.invoice_days'), 'calendar-check-o', []) }}

                        {{ Form::radioGroup('send_bill_reminder', trans('settings.scheduling.send_bill')) }}

                        {{ Form::textGroup('schedule_bill_days', trans('settings.scheduling.bill_days'), 'calendar-check-o', []) }}

                        {{ Form::radioGroup('send_item_reminder', trans('settings.scheduling.send_item_reminder')) }}

                        {{ Form::textGroup('schedule_item_stocks', trans('settings.scheduling.item_stocks'), 'cubes', []) }}

                        <div class="col-sm-6">
                            <label for="cron_command" class="control-label">{{ trans('settings.scheduling.cron_command') }}</label>
                            <pre>php /path-to-akaunting/artisan schedule:run >> /dev/null 2>&1</pre>
                        </div>

                        {{ Form::textGroup('schedule_time', trans('settings.scheduling.schedule_time'), 'clock-o', []) }}
                    </div>

                    <div class="tab-pane tab-margin" id="appearance">
                        {{ Form::selectGroup('admin_theme', trans('settings.appearance.theme'), 'paint-brush', ['skin-green-light' => trans('settings.appearance.light'), 'skin-black' => trans('settings.appearance.dark')], null, []) }}

                        {{ Form::selectGroup('list_limit', trans('settings.appearance.list_limit'), 'columns', ['10' => '10', '25' => '25', '50' => '50', '100' => '100'], null, []) }}

                        {{ Form::radioGroup('use_gravatar', trans('settings.appearance.use_gravatar')) }}
                    </div>

                    <div class="tab-pane tab-margin" id="system">
                        {{ Form::selectGroup('session_handler', trans('settings.system.session.handler'), 'database', ['file' => trans('settings.system.session.file'), 'database' => trans('settings.system.session.database')], null, []) }}

                        {{ Form::textGroup('session_lifetime', trans('settings.system.session.lifetime'), 'clock-o', []) }}

                        {{ Form::textGroup('file_size', trans('settings.system.file_size'), 'upload', []) }}

                        {{ Form::textGroup('file_types', trans('settings.system.file_types'), 'file-o', []) }}
                    </div>

                    @permission('update-settings-settings')
                    <div class="setting-buttons">
                        <div class="form-group no-margin">
                            {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'submit', 'class' => 'btn btn-success  button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                            <a href="{{ URL::previous() }}" class="btn btn-default"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</a>
                        </div>
                    </div>
                    @endpermission
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @if (language()->getShortCode() != 'en')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/locales/bootstrap-datepicker.' . language()->getShortCode() . '.js') }}"></script>
    @endif
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function() {
            $('#email_smtp_password').attr('type', 'password');

            $("#date_format").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.localisation.date.format')]) }}"
            });

            $("#date_separator").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.localisation.date.separator')]) }}"
            });

            $("#timezone").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.localisation.timezone')]) }}"
            });

            $("#percent_position").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.localisation.percent.title')]) }}"
            });

            $("#default_account").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.default.account')]) }}"
            });

            $("#default_currency").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.default.currency')]) }}"
            });

            $("#default_tax").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.default.tax')]) }}"
            });

            $("#default_payment_method").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.default.payment')]) }}"
            });

            $("#default_locale").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.default.language')]) }}"
            });

            $("#admin_theme").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.appearance.theme')]) }}"
            });

            $("#email_protocol").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.email.protocol')]) }}"
            });

            $("#list_limit").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.appearance.list_limit')]) }}"
            });

            $("#session_handler").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('settings.system.session.handler')]) }}"
            });

            $('#financial_start').datepicker({
                format: 'dd-mm',
                todayBtn: 'linked',
                weekStart: 1,
                autoclose: true,
                language: '{{ language()->getShortCode() }}'
            });

            $('#company_logo').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($setting['company_logo'])
                placeholder : '{{ $setting['company_logo']->basename }}',
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}',
                @endif
            });

            @if($setting['company_logo'])
            $.ajax({
                url: '{{ url('uploads/' . $setting['company_logo']->id . '/show') }}',
                type: 'GET',
                data: {column_name: 'company_logo', page: 'setting', key: 'general.company_logo'},
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('#company .fancy-file').after(json['html']);
                    }
                }
            });

            @permission('delete-common-uploads')
            $(document).on('click', '#remove-company_logo', function (e) {
                confirmDelete("#company_logo-{!! $setting['company_logo']->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $setting['company_logo']->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endpermission
            @endif

            var invoice_file = false;

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");

                if (target == '#invoice' && !invoice_file) {
                    $(target + ' #invoice_logo').fancyfile({
                        text  : '{{ trans('general.form.select.file') }}',
                        style : 'btn-default',
                        @if($setting['invoice_logo'])
                        placeholder : '{{ $setting['invoice_logo']->basename }}',
                        @else
                        placeholder : '{{ trans('general.form.no_file_selected') }}',
                        @endif
                    });

                    @if($setting['invoice_logo'])
                    $.ajax({
                        url: '{{ url('uploads/' . $setting['invoice_logo']->id . '/show') }}',
                        type: 'GET',
                        data: {column_name: 'invoice_logo', page: 'setting', key: 'general.invoice_logo'},
                        dataType: 'JSON',
                        success: function(json) {
                            if (json['success']) {
                                $(target + ' .fancy-file').after(json['html']);
                            }
                        }
                    });

                    @permission('delete-common-uploads')
                    $(document).on('click', '#remove-invoice_logo', function (e) {
                        confirmDelete("#invoice_logo-{!! $setting['invoice_logo']->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $setting['invoice_logo']->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
                    });
                    @endpermission
                    @endif

                    invoice_file = true;
                }
            });

            $("select[name='email_protocol']").on('change', function() {
                var selection = $(this).val();

                if (selection == 'mail' || selection == 'log') {
                    $("input[name='email_sendmail_path']").prop('disabled', true);
                    $("input[name='email_smtp_host']").prop('disabled', true);
                    $("input[name='email_smtp_username']").prop('disabled', true);
                    $("input[name='email_smtp_password']").prop('disabled', true);
                    $("input[name='email_smtp_port']").prop('disabled', true);
                    $("select[name='email_smtp_encryption']").prop('disabled', true);
                } else if(selection == 'sendmail') {
                    $("input[name='email_sendmail_path']").prop('disabled', false);
                    $("input[name='email_smtp_host']").prop('disabled', true);
                    $("input[name='email_smtp_username']").prop('disabled', true);
                    $("input[name='email_smtp_password']").prop('disabled', true);
                    $("input[name='email_smtp_port']").prop('disabled', true);
                    $("select[name='email_smtp_encryption']").prop('disabled', true);
                } else if (selection == 'smtp') {
                    $("input[name='email_sendmail_path']").prop('disabled', true);
                    $("input[name='email_smtp_host']").prop('disabled', false);
                    $("input[name='email_smtp_username']").prop('disabled', false);
                    $("input[name='email_smtp_password']").prop('disabled', false);
                    $("input[name='email_smtp_port']").prop('disabled', false);
                    $("select[name='email_smtp_encryption']").prop('disabled', false);
                }
            });

            $("select[name='email_protocol']").trigger('change');
        });
    </script>
@endpush
