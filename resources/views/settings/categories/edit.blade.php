@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.categories', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($category, [
            'method' => 'PATCH',
            'url' => ['settings/categories', $category->id],
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            @if ($type_disabled)
                {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, null, ['required' => 'required', 'disabled' => 'disabled']) }}
                <input type="hidden" name="type" value="{{ $category->type }}" />
            @else
                {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types) }}
            @endif

            @stack('color_input_start')
            <div class="form-group col-md-6 required {{ $errors->has('color') ? 'has-error' : ''}}">
                {!! Form::label('color', trans('general.color'), ['class' => 'control-label']) !!}
                <div  id="category-color-picker" class="input-group colorpicker-component">
                    <div class="input-group-addon"><i></i></div>
                    {!! Form::text('color', null, ['id' => 'color', 'class' => 'form-control', 'required' => 'required']) !!}
                </div>
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </div>
            @stack('color_input_end')

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-settings-categories')
        <div class="box-footer">
            {{ Form::saveButtons('settings/categories') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function(){
            $("#type").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]) }}"
            });

            $('#category-color-picker').colorpicker();
        });
    </script>
@endpush
