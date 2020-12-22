@extends('layouts.admin')

@section('title', trans('import.title', ['type' => trans_choice($namespace . 'general.' . $type, 2)]))

@section('content')
    <div class="card">
        {!! Form::open([
            'url' => $path . '/import',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-important">
                            {!! trans('import.message', ['link' => url('public/files/import/' . $type . '.xlsx')]) !!}
                        </div>
                    </div>
                </div>

                @stack('import_input_start')
                    <div class="dropzone dropzone-single" data-toggle="dropzone" data-dropzone-url="#">
                        <div class="fallback">
                            <div class="custom-file">
                                <input type="file" name="import" class="custom-file-input" id="projectCoverUploads">
                                <label class="custom-file-label" for="projectCoverUploads">{{ trans('general.form.no_file_selected') }}</label>
                            </div>
                        </div>
                        <div class="dz-preview dz-preview-single">
                            <div class="dz-preview-cover">
                                <img class="dz-preview-img" src="..." alt="..." data-dz-thumbnail>
                            </div>
                        </div>
                        {!! $errors->first('import', '<p class="help-block">:message</p>') !!}
                    </div>
                @stack('import_input_end')
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    <div class="col-xs-12 col-sm-12">
                        <a href="{{ url($path) }}" class="btn btn-outline-secondary header-button-top"><span class="fa fa-times"></span> &nbsp;{{ trans('general.cancel') }}</a>
                        {!! Form::button('<span class="fa fa-download"></span> &nbsp;' . trans('import.import'), ['type' => 'submit', 'class' => 'btn btn-success header-button-top']) !!}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
