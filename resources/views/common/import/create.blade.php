@extends('layouts.admin')

@section('title', trans('import.title', ['type' => $title_type]))

@section('content')
    <div class="card">
        {!! Form::open($form_params) !!}

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning alert-important">
                            {!! trans('import.limitations', ['extensions' => strtoupper(config('excel.imports.extensions')), 'row_limit' => config('excel.imports.row_limit')]) !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="alert alert-info alert-important">
                            {!! trans('import.sample_file', ['download_link' => $sample_file]) !!}
                        </div>
                    </div>

                    {{ Form::fileGroup('import', '', 'plus', ['dropzone-class' => 'form-file', 'options' => ['acceptedFiles' => '.xls,.xlsx']], null, 'col-md-12') }}
                </div>
            </div>

            <div class="card-footer">
                <div class="row save-buttons">
                    <div class="col-xs-12 col-sm-12">
                        @if (!empty($route))
                            <a href="{{ route(\Str::replaceFirst('.import', '.index', $route)) }}" class="btn btn-outline-secondary">
                                {{ trans('general.cancel') }}
                            </a>
                        @else
                            <a href="{{ url($path) }}" class="btn btn-outline-secondary">
                                {{ trans('general.cancel') }}
                            </a>
                        @endif

                        {!! Form::button(
                            '<span v-if="form.loading" class="btn-inner--icon"><i class="aka-loader"></i></span> <span :class="[{\'ml-0\': form.loading}]" class="btn-inner--text">' . trans('import.import') . '</span>',
                            [':disabled' => 'form.loading', 'type' => 'submit', 'class' => 'btn btn-icon btn-success']) !!}
                    </div>
                </div>
            </div>

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/imports.js?v=' . version('short')) }}"></script>
@endpush
