@extends('layouts.admin')

@section('title', trans('import.title', ['type' => trans_choice($namespace . 'general.' . $type, 2)]))

@section('content')
    <div class="card">
        @php 
        $form_open = [
            'id' => 'import',
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ];

        if (!empty($route)) {
            $form_open['route'] = $route;
        } else {
            $form_open['url'] = $path . '/import';
        }
        @endphp
        {!! Form::open($form_open) !!}

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-important">
                            {!! trans('import.message', ['link' => url('public/files/import/' . $type . '.xlsx')]) !!}
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
