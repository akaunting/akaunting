@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.reports', 1)]))

@section('content')
    <div class="card">
        {!! Form::model($report, [
            'id' => 'report',
            'method' => 'PATCH',
            'route' => ['reports.update', $report->id],
            '@submit.prevent' => 'onSubmit',
            '@keydown' => 'form.errors.clear($event.target.name)',
            'role' => 'form',
            'class' => 'form-loading-button',
            'novalidate' => true,
        ]) !!}

            <div class="card-body">
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'font') }}

                    {{ Form::textGroup('class_disabled', trans_choice('general.types', 1), 'bars', ['required' => 'required', 'disabled' => 'true'], $classes[$report->class]) }}
                    {{ Form::hidden('class', $report->class) }}

                    {{ Form::textareaGroup('description', trans('general.description'), null, null, ['rows' => '3', 'required' => 'required']) }}

                    {{ Form::hidden('report', 'invalid', ['data-field' => 'settings']) }}

                    @foreach($class->getFields() as $field)
                        @php $type = $field['type']; @endphp

                        @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
                            {{ Form::$type($field['name'], $field['title'], $field['icon'], array_merge([
                                    'data-field' => 'settings'
                                ],
                                $field['attributes'])
                            ) }}
                        @elseif ($type == 'textareaGroup')
                            {{ Form::$type($field['name'], $field['title']) }}
                        @elseif ($type == 'selectGroup')
                            {{ Form::$type($field['name'], $field['title'], $field['icon'], $field['values'], $report->settings->{$field['name']}, array_merge([
                                    'data-field' => 'settings'
                                ],
                                $field['attributes'])
                            ) }}
                        @elseif ($type == 'radioGroup')
                            {{ Form::$type($field['name'], $field['title'], isset($report->settings->{$field['name']}) ? $report->settings->{$field['name']} : 1, $field['enable'], $field['disable'], array_merge([
                                    'data-field' => 'settings'
                                ],
                                $field['attributes'])
                            ) }}
                        @elseif ($type == 'checkboxGroup')
                            {{ Form::$type($field['name'], $field['title'], $field['items'], $report->settings->{$field['name']}, $field['id'], array_merge([
                                    'data-field' => 'settings'
                                ],
                                $field['attributes'])
                            ) }}
                        @endif
                    @endforeach
                </div>
            </div>

            @permission('update-common-reports')
                <div class="card-footer">
                    <div class="row save-buttons">
                        {{ Form::saveButtons('reports.index') }}
                    </div>
                </div>
            @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush
