@extends('layouts.admin')

@section('title', $module->getName())

@section('content')
    <div class="card">
        {!! Form::model($setting, [
            'id' => 'module',
            'method' => 'PATCH',
            'route' => ['settings.module.update', $module->getAlias()],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
        ]) !!}

            <div class="card-body">
                <div class="row">
                    @foreach($module->get('settings') as $field)
                        @php $type = $field['type']; @endphp

                        @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup') || ($type == 'numberGroup'))
                            {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['attributes']) }}
                        @elseif ($type == 'textareaGroup')
                            {{ Form::$type($field['name'], trans($field['title'])) }}
                        @elseif ($type == 'selectGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['values'], $field['selected'], $field['attributes']) }}
                        @elseif ($type == 'radioGroup')
                            {{ Form::$type($field['name'], trans($field['title']), isset($setting[$field['name']]) ? $setting[$field['name']] : 1, trans($field['enable']), trans($field['disable']), $field['attributes']) }}
                        @elseif ($type == 'checkboxGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['items'], $field['value'], $field['id'], $field['selected'], $field['attributes']) }}
                        @elseif ($type == 'fileGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['attributes']) }}
                        @elseif ($type == 'dateGroup')
                            {{ Form::$type($field['name'], trans($field['title']), $field['icon'], array_merge(['id' => $field['name'], 'date-format' => 'Y-m-d', 'show-date-format' => company_date_format(), 'autocomplete' => 'off'], $field['attributes']), Date::parse($setting[$field['name']] ?? now())->toDateString()) }}
                        @elseif ($type == 'accountSelectGroup')
                            {{ Form::selectGroup($field['name'], trans_choice('general.accounts', 1), 'university', $accounts, setting($module->getAlias() . '.' . $field['name']), $field['attributes']) }}
                        @elseif ($type == 'categorySelectGroup')
                            {{ Form::selectGroup($field['name'], trans_choice('general.categories', 1), 'folder', $categories, setting($module->getAlias() . '.' . $field['name']), $field['attributes']) }}
                        @endif
                    @endforeach

                    {{ Form::hidden('module_alias', $module->getAlias(), ['id' => 'module_alias']) }}
                </div>
            </div>

            @can('update-' . $module->getAlias() . '-settings')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('settings.index') }}
                    </div>
                </div>
            @endcan

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/settings/modules.js?v=' . version('short')) }}"></script>
@endpush
