@extends('layouts.admin')

@section('title', trans('general.email'))

@section('content')
    {!! Form::model($setting, [
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.email.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="row">
        <div class="col-md-6">
            <div class="accordion" id="accordion1">
                <div class="card">
                    <div class="card-header" id="heading1" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_new_customer') }}</h4>
                        </div>
                    </div>
                    <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordion1">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_new_customer_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_new_customer_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $invoice_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion2">
                <div class="card">
                    <div class="card-header" id="heading2" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_remind_customer') }}</h4>
                        </div>
                    </div>
                    <div id="collapse2" class="collapse show" aria-labelledby="heading2" data-parent="#accordion2">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_remind_customer_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_remind_customer_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $invoice_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion3">
                <div class="card">
                    <div class="card-header" id="heading3" data-toggle="collapse" data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_remind_admin') }}</h4>
                        </div>
                    </div>
                    <div id="collapse3" class="collapse hide" aria-labelledby="heading3" data-parent="#accordion3">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_remind_admin_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_remind_admin_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $invoice_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion4">
                <div class="card">
                    <div class="card-header" id="heading4" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_recur_customer') }}</h4>
                        </div>
                    </div>
                    <div id="collapse4" class="collapse hide" aria-labelledby="heading4" data-parent="#accordion4">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_recur_customer_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_recur_customer_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $invoice_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion5">
                <div class="card">
                    <div class="card-header" id="heading5" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_recur_admin') }}</h4>
                        </div>
                    </div>
                    <div id="collapse5" class="collapse hide" aria-labelledby="heading5" data-parent="#accordion5">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_recur_admin_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_recur_admin_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $invoice_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion6">
                <div class="card">
                    <div class="card-header" id="heading6" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_payment_customer') }}</h4>
                        </div>
                    </div>
                    <div id="collapse6" class="collapse hide" aria-labelledby="heading6" data-parent="#accordion6">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_payment_customer_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_payment_customer_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $payment_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion7">
                <div class="card">
                    <div class="card-header" id="heading7" data-toggle="collapse" data-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.invoice_payment_admin') }}</h4>
                        </div>
                    </div>
                    <div id="collapse7" class="collapse hide" aria-labelledby="heading7" data-parent="#accordion7">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_invoice_payment_admin_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_invoice_payment_admin_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $payment_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion8">
                <div class="card">
                    <div class="card-header" id="heading8" data-toggle="collapse" data-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.bill_remind_admin') }}</h4>
                        </div>
                    </div>
                    <div id="collapse8" class="collapse hide" aria-labelledby="heading8" data-parent="#accordion8">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_bill_remind_admin_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_bill_remind_admin_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $bill_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="accordion" id="accordion9">
                <div class="card">
                    <div class="card-header" id="heading9" data-toggle="collapse" data-target="#collapse9" aria-expanded="false" aria-controls="collapse9">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.templates.bill_recur_admin') }}</h4>
                        </div>
                    </div>
                    <div id="collapse9" class="collapse hide" aria-labelledby="heading9" data-parent="#accordion9">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_bill_recur_admin_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], null, 'col-md-12') }}

                                {{ Form::textareaGroup('template_bill_recur_admin_body', trans('settings.email.templates.body'), null, null, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12') }}

                                <div class="col-md-12">
                                    <small class="text-gray">{!! trans('settings.email.templates.tags', ['tag_list' => $bill_tags]) !!}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="accordion" id="accordion10">
                <div class="card">
                    <div class="card-header" id="heading10" data-toggle="collapse" data-target="#collapse10" aria-expanded="false" aria-controls="collapse10">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.protocol') }}</h4>
                        </div>
                    </div>
                    <div id="collapse10" class="collapse hide" aria-labelledby="heading10" data-parent="#accordion10">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::selectGroup('protocol', trans('settings.email.protocol'), 'share', $email_protocols, !empty($setting['protocol']) ? $setting['protocol'] : null, []) }}

                                {{ Form::textGroup('sendmail_path', trans('settings.email.sendmail_path'), 'road', [':disabled' => '(form.protocol == "smtp")|| (form.protocol != "sendmail") ? true : false']) }}

                                {{ Form::textGroup('smtp_host', trans('settings.email.smtp.host'), 'paper-plane', [':disabled' => '(form.protocol != "smtp") ? true : false']) }}

                                {{ Form::textGroup('smtp_port', trans('settings.email.smtp.port'), 'paper-plane', [':disabled' => '(form.protocol != "smtp") ? true : false']) }}

                                {{ Form::textGroup('smtp_username', trans('settings.email.smtp.username'), 'paper-plane', [':disabled' => '(form.protocol != "smtp") ? true : false']) }}

                                {{ Form::textGroup('smtp_password', trans('settings.email.smtp.password'), 'paper-plane', ['type' => 'password',':disabled' => '(form.protocol != "smtp") ? true : false']) }}

                                {{ Form::selectGroup('smtp_encryption', trans('settings.email.smtp.encryption'), 'paper-plane', ['' => trans('settings.email.smtp.none'), 'ssl' => 'SSL', 'tls' => 'TLS'], !empty($setting['smtp_encryption']) ? $setting['smtp_encryption'] : null, [':disabled' => '(form.protocol != "smtp") ? true : false']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('update-settings-settings')
        <div class="row ml-0 mr-0">
            <div class="card col-md-12">
                <div class="card-body mr--3">
                    <div class="row float-right">
                        {{ Form::saveButtons(URL::previous()) }}
                    </div>
                </div>
            </div>
        </div>
    @endpermission

    {!! Form::hidden('_prefix', 'email') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
