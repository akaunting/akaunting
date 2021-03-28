@extends('layouts.admin')

@section('title', trans('general.email'))

@section('content')
    {!! Form::open([
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

    @php $card = 1; @endphp

    <div class="row">

    @foreach($templates as $template)
        @php
            if (!class_exists($template->class)) {
                continue;
            }
            $aria_expanded_status = in_array($card, [1, 2]) ? 'true' : 'false';
            $collapse_status = in_array($card, [1, 2]) ? 'show' : '';
        @endphp

        <div class="col-md-6">
            <div class="accordion" id="accordion-{{ $card }}">
                <div class="card">
                    <div class="card-header" id="heading-{{ $card }}" data-toggle="collapse" data-target="#collapse-{{ $card }}" aria-expanded="{{ $aria_expanded_status }}" aria-controls="collapse-{{ $card }}">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans($template->name) }}</h4>
                        </div>
                    </div>

                    <div id="collapse-{{ $card }}" class="collapse {{ $collapse_status }}" aria-labelledby="heading-{{ $card }}" data-parent="#accordion-{{ $card }}">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::textGroup('template_' . $template->alias . '_subject', trans('settings.email.templates.subject'), 'font', ['required' => 'required'], $template->subject, 'col-md-12') }}

                                {{ Form::textEditorGroup('template_' . $template->alias . '_body', trans('settings.email.templates.body'), null, $template->body, ['required' => 'required', 'rows' => '5', 'data-toggle' => 'quill'], 'col-md-12 mb-0') }}

                                <div class="col-md-12">
                                    <div class="bg-secondary border-radius-default border-1 p-2">
                                        <small class="text-default">{!! trans('settings.email.templates.tags', ['tag_list' => implode(', ', app($template->class)->getTags())]) !!}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php $card++; @endphp
    @endforeach

        <div class="col-md-12">
            <div class="accordion" id="accordion-{{ $card }}">
                <div class="card">
                    <div class="card-header" id="heading-{{ $card }}" data-toggle="collapse" data-target="#collapse-{{ $card }}" aria-expanded="false" aria-controls="collapse-{{ $card }}">
                        <div class="align-items-center">
                            <h4 class="mb-0">{{ trans('settings.email.protocol') }}</h4>
                        </div>
                    </div>

                    <div id="collapse-{{ $card }}" class="collapse hide" aria-labelledby="heading-{{ $card }}" data-parent="#accordion-{{ $card }}">
                        <div class="card-body">
                            <div class="row">
                                {{ Form::selectGroup('protocol', trans('settings.email.protocol'), 'share', $email_protocols, setting('email.protocol'), ['change' => 'onChangeProtocol']) }}

                                {{ Form::textGroup('sendmail_path', trans('settings.email.sendmail_path'), 'road', [':disabled'=> 'email.sendmailPath'], setting('email.sendmail_path')) }}

                                {{ Form::textGroup('smtp_host', trans('settings.email.smtp.host'), 'paper-plane', [':disabled' => 'email.smtpHost'], setting('email.smtp_host')) }}

                                {{ Form::textGroup('smtp_port', trans('settings.email.smtp.port'), 'paper-plane', [':disabled' => 'email.smtpPort'], setting('email.smtp_port')) }}

                                {{ Form::textGroup('smtp_username', trans('settings.email.smtp.username'), 'paper-plane', [':disabled' => 'email.smtpUsername'], setting('email.smtp_username')) }}

                                {{ Form::textGroup('smtp_password', trans('settings.email.smtp.password'), 'paper-plane', ['type' => 'password', ':disabled' => 'email.smtpPassword'], setting('email.smtp_password')) }}

                                {{ Form::selectGroup('smtp_encryption', trans('settings.email.smtp.encryption'), 'paper-plane', ['' => trans('settings.email.smtp.none'), 'ssl' => 'SSL', 'tls' => 'TLS'], setting('email.smtp_encryption', null), ['disabled' => 'email.smtpEncryption']) }}
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('update-settings-settings')
        <div class="row ml-0 mr-0">
            <div class="card col-md-12">
                <div class="card-body mr--3">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('settings.index') }}
                    </div>
                </div>
            </div>
        </div>
    @endcan

    {!! Form::hidden('_prefix', 'email') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
