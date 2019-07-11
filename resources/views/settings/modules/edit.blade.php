@extends('layouts.admin')

@section('title', $module->getName())

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($setting, [
            'method' => 'PATCH',
            'url' => ['settings/apps/' . $module->getAlias()],
            'files' => true,
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            @foreach($module->get('settings') as $field)
                @php $type = $field['type']; @endphp

                @if (($type == 'textGroup') || ($type == 'emailGroup') || ($type == 'passwordGroup'))
                    {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['attributes']) }}
                @elseif ($type == 'textareaGroup')
                    {{ Form::$type($field['name'], trans($field['title'])) }}
                @elseif ($type == 'selectGroup')
                    {{ Form::$type($field['name'], trans($field['title']), $field['icon'], $field['values'], $field['selected'], $field['attributes']) }}
                @elseif ($type == 'radioGroup')
                    {{ Form::$type($field['name'], trans($field['title']), trans($field['enable']), trans($field['disable']), $field['attributes']) }}
                @elseif ($type == 'checkboxGroup')
                    {{ Form::$type($field['name'], trans($field['title']), $field['items'], $field['value'], $field['id'], $field['attributes']) }}
                @elseif ($type == 'fileGroup')
                    {{ Form::$type($field['name'], trans($field['title']), $field['attributes']) }}
                @endif
            @endforeach
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons(URL::previous()) }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';
    </script>
@endpush
