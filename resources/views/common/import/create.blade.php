@extends('layouts.admin')

@section('title', trans('import.title', ['type' => trans_choice('general.' . $type, 2)]))

@section('content')
    <div class="box box-success">
        {!! Form::open(['url' => $path . '/import', 'files' => true, 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            <div class="col-md-12">
                <div class="alert alert-info alert-important">
                    {!! trans('import.message', ['link' => url(AKAUNTING_PUBLIC . 'files/import/' . $type . '.xlsx')]) !!}
                </div>
            </div>
            @stack('import_input_start')
            <div class="form-group col-md-12 required {{ $errors->has('import') ? 'has-error' : '' }}" style="min-height: 59px">
                {!! Form::label('import', trans('general.form.select.file'), ['class' => 'control-label']) !!}
                {!! Form::file('import', null, ['class' => 'form-control']) !!}
                {!! $errors->first('import', '<p class="help-block">:message</p>') !!}
            </div>
            @stack('import_input_end')
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <div class="col-md-12">
                <div class="form-group no-margin">
                    {!! Form::button('<span class="fa fa-download"></span> &nbsp;' . trans('import.import'), ['type' => 'submit', 'class' => 'btn btn-success']) !!}
                    <a href="{{ url($path) }}" class="btn btn-default"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</a>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
<script src="{{ asset(AKAUNTING_PUBLIC . 'js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset(AKAUNTING_PUBLIC . 'css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('#import').fancyfile({
            text  : '{{ trans('general.form.select.file') }}',
            style : 'btn-default',
            placeholder : '{{ trans('general.form.no_file_selected') }}'
        });
    });
</script>
@endpush
