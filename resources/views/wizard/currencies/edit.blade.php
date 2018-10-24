@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
<!-- Default box -->
<div class="box box-solid">
    <div class="box-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/companies') }}" type="button" class="btn btn-default btn-circle">1</a>
                    <p><small>{{ trans_choice('general.companies', 1) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-2" type="button" class="btn btn-success btn-circle">2</a>
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
    {!! Form::model([], ['method' => 'PATCH', 'files' => true, 'url' => ['wizard/currencies'], 'role' => 'form', 'class' => 'form-loading-button']) !!}

    <div class="box-header with-border">
        <h3 class="box-title">{{ trans_choice('general.currencies', 1) }}</h3>
    </div>
    <!-- /.box-header -->

    <div class="box-body">

        {{ Form::textGroup('company_tax_number', trans('general.tax_number'), 'percent', []) }}

        {{ Form::textGroup('company_phone', trans('settings.company.phone'), 'phone', []) }}

        {{ Form::textareaGroup('company_address', trans('settings.company.address')) }}
    </div>
    <!-- /.box-body -->

    <div class="box-footer">
        <div class="col-md-12">
            <div class="form-group no-margin">
                {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'submit', 'class' => 'btn btn-success  button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                <a href="{{ url('wizard/skip') }}" class="btn btn-default"><span class="fa fa-share"></span> &nbsp;{{ trans('general.skip') }}</a>
            </div>
        </div>
    </div>
    <!-- /.box-footer -->

    {!! Form::close() !!}
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var text_yes = '{{ trans('general.yes') }}';
    var text_no = '{{ trans('general.no') }}';

    $(document).ready(function() {

    });
</script>
@endpush
