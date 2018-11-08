@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
<!-- Default box -->
<div class="box box-solid">
    <div class="box-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
                    <p><small>{{ trans_choice('general.companies', 1) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <button type="button" class="btn btn-default btn-circle" disabled="disabled">2</button>
                    <p><small>{{ trans_choice('general.currencies', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <button type="button" class="btn btn-default btn-circle" disabled="disabled">3</button>
                    <p><small>{{ trans_choice('general.taxes', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <button type="button" class="btn btn-default btn-circle" disabled="disabled">4</button>
                    <p><small>{{ trans_choice('general.finish', 1) }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">
    <div id="wizard-loading"></div>

    {!! Form::model($company, ['method' => 'PATCH', 'files' => true, 'url' => ['wizard/companies'], 'role' => 'form', 'class' => 'form-loading-button']) !!}

    <div class="box-header with-border">
        <h3 class="box-title">{{ trans_choice('general.companies', 1) }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">
        <div class="col-md-12 {!! (!setting('general.api_token', null)) ?: 'hidden' !!}">
            <div class="form-group required {{ $errors->has('api_token') ? 'has-error' : ''}}">
                {!! Form::label('sale_price', trans('modules.api_token'), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    {!! Form::text('api_token', setting('general.api_token', null), ['class' => 'form-control', 'required' => 'required', 'placeholder' => trans('general.form.enter', ['field' => trans('modules.api_token')])]) !!}
                </div>
                {!! $errors->first('api_token', '<p class="help-block">:message</p>') !!}
            </div>
            <p>
                {!! trans('modules.token_link') !!}
            </p>
            </br>
        </div>

        {{ Form::textGroup('company_tax_number', trans('general.tax_number'), 'percent', []) }}

        {{ Form::textGroup('company_phone', trans('settings.company.phone'), 'phone', []) }}

        {{ Form::textareaGroup('company_address', trans('settings.company.address')) }}

        {{ Form::fileGroup('company_logo', trans('settings.company.logo')) }}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            <div class="form-group no-margin">
                {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'submit', 'class' => 'btn btn-success  button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                <a href="{{ url('wizard/currencies') }}" id="wizard-skip" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
            </div>
        </div>
    </div>
    <!-- /.box-footer -->

    {!! Form::close() !!}
</div>
@endsection

@push('js')
<script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
<script type="text/javascript">
    var text_yes = '{{ trans('general.yes') }}';
    var text_no = '{{ trans('general.no') }}';

    $(document).ready(function() {
        $('#company_logo').fancyfile({
            text  : '{{ trans('general.form.select.file') }}',
            style : 'btn-default',
            @if($company->company_logo)
            placeholder : '{{ $company->company_logo->basename }}',
            @else
            placeholder : '{{ trans('general.form.no_file_selected') }}',
            @endif
        });

        @if($company->company_logo)
        company_logo_html  = '<span class="company_logo">';
        company_logo_html += '    <a href="{{ url('uploads/' . $company->company_logo->id . '/download') }}">';
        company_logo_html += '        <span id="download-company_logo" class="text-primary">';
        company_logo_html += '            <i class="fa fa-file-{{ $company->company_logo->aggregate_type }}-o"></i> {{ $company->company_logo->basename }}';
        company_logo_html += '        </span>';
        company_logo_html += '    </a>';
        company_logo_html += '    {!! Form::open(['id' => 'company_logo-' . $company->company_logo->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $company->company_logo->id)], 'style' => 'display:inline']) !!}';
        company_logo_html += '    <a id="remove-company_logo" href="javascript:void();">';
        company_logo_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
        company_logo_html += '    </a>';
        company_logo_html += '    <input type="hidden" name="page" value="setting" />';
        company_logo_html += '    <input type="hidden" name="key" value="general.company_logo" />';
        company_logo_html += '    <input type="hidden" name="value" value="{{ $company->company_logo->id }}" />';
        company_logo_html += '    {!! Form::close() !!}';
        company_logo_html += '</span>';

        $('.form-group.col-md-6 .fancy-file .fake-file').append(company_logo_html);

        $(document).on('click', '#remove-company_logo', function (e) {
            confirmDelete("#company_logo-{!! $company->company_logo->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $company->company_logo->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
        });
        @endif
    });
</script>
@endpush
