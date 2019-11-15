@extends('layouts.admin')

@section('title', $module->getName())

@section('content')
    <div class="card">
        {!! Form::model($setting, [
            'id' => 'module',
            'method' => 'PATCH',
            'url' => ['settings/' . $module->getAlias()],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true
        ]) !!}

            <div class="card-body">
                <div class="row">
                    @foreach($module->get('settings') as $field)
                        @php $type = $field['type']; @endphp

                        @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
                            {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['attributes']) }}
                        @elseif ($type == 'textareaGroup')
                            {{ Form::$type($field['name'], trans($field['title'])) }}
                        @elseif ($type == 'selectGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['values'], $field['selected'], $field['attributes']) }}
                        @elseif ($type == 'radioGroup')
                            {{ Form::$type($field['name'], trans($field['title']), null, trans($field['enable']), trans($field['disable']), $field['attributes']) }}
                        @elseif ($type == 'checkboxGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['items'], $field['value'], $field['id'], $field['attributes']) }}
                        @elseif ($type == 'fileGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['attributes']) }}
                        @endif
                    @endforeach

                    {{ Form::hidden('module_alias', $module->getAlias(), ['id' => 'module_alias']) }}
                </div>
            </div>

            <div class="card-footer">
                <div class="float-right">
                    <div class="row">
                        {{ Form::saveButtons(URL::previous()) }}
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/modules.js?v=' . version('short')) }}"></script>
@endpush
