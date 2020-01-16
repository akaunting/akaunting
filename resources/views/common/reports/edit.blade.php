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

                    {{ Form::selectGroup('class', trans_choice('general.types', 1), 'bars', $classes, $report->class, ['required' => 'required', 'change' => 'onChangeClass']) }}

                    {{ Form::textareaGroup('description', trans('general.description'), null, null, ['rows' => '3', 'required' => 'required']) }}

                    @foreach($class->getFields() as $field)
                        @php $type = $field['type']; @endphp

                        @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
                            {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['icon'], $field['attributes']) }}
                        @elseif ($type == 'textareaGroup')
                            {{ Form::$type('settings[' . $field['name'] . ']', $field['title']) }}
                        @elseif ($type == 'selectGroup')
                            {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['icon'], $field['values'], $report->settings->{$field['name']}, $field['attributes']) }}
                        @elseif ($type == 'radioGroup')
                            {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], isset($report->settings->{$field['name']}) ? $report->settings->{$field['name']} : 1, $field['enable'], $field['disable'], $field['attributes']) }}
                        @elseif ($type == 'checkboxGroup')
                            {{ Form::$type('settings[' . $field['name'] . ']', $field['title'], $field['items'], $report->settings->{$field['name']}, $field['id'], $field['attributes']) }}
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card-footer">
                <div class="row float-right">
                    {{ Form::saveButtons('common/reports') }}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts_start')
    <script type="text/javascript">
        var class = '';
    </script>

    <script src="{{ asset('public/js/common/reports.js?v=' . version('short')) }}"></script>
@endpush
