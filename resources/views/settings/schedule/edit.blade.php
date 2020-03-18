@extends('layouts.admin')

@section('title', trans('settings.scheduling.name'))

@section('content')
    {!! Form::model($setting, [
        'id' => 'setting',
        'method' => 'PATCH',
        'route' => 'settings.update',
        '@submit.prevent' => 'onSubmit',
        '@keydown' => 'form.errors.clear($event.target.name)',
        'files' => true,
        'role' => 'form',
        'class' => 'form-loading-button',
        'novalidate' => true,
    ]) !!}

    <div class="card">
        <div class="card-body">
            <div class="row">
                {{ Form::radioGroup('send_invoice_reminder', trans('settings.scheduling.send_invoice'), $setting->get('send_invoice_reminder')) }}

                {{ Form::textGroup('invoice_days', trans('settings.scheduling.invoice_days'), 'calendar', []) }}

                {{ Form::radioGroup('send_bill_reminder', trans('settings.scheduling.send_bill'), $setting->get('send_bill_reminder')) }}

                {{ Form::textGroup('bill_days', trans('settings.scheduling.bill_days'), 'calendar', []) }}

                <div class="col-sm-6">
                    <label for="cron_command" class="form-control-label">{{ trans('settings.scheduling.cron_command') }}</label>
                    <input type="text" class="form-control form-control-muted" value="php {{ base_path('artisan') }} schedule:run >> /dev/null 2>&1">
                </div>

                {{ Form::textGroup('time', trans('settings.scheduling.schedule_time'), 'clock', []) }}
            </div>
        </div>

        @permission('update-settings-settings')
            <div class="card-footer">
                <div class="row save-buttons">
                    {{ Form::saveButtons('settings.index') }}
                </div>
            </div>
        @endpermission
    </div>

    {!! Form::hidden('_prefix', 'schedule') !!}

    {!! Form::close() !!}
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/settings.js?v=' . version('short')) }}"></script>
@endpush
