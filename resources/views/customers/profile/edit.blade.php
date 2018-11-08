@extends('layouts.customer')

@section('title', trans('general.title.edit', ['type' => trans('auth.profile')]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($user, [
            'method' => 'PATCH',
            'files' => true,
            'url' => ['customers/profile/update'],
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::emailGroup('email', trans('general.email'), 'envelope') }}

            {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

            {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

            {{ Form::textareaGroup('address', trans('general.address')) }}

            {{ Form::passwordGroup('password', trans('auth.password.current'), 'key', []) }}

            {{ Form::passwordGroup('password_confirmation', trans('auth.password.current_confirm'), 'key', []) }}

            {{ Form::selectGroup('locale', trans_choice('general.languages', 1), 'flag', language()->allowed()) }}

            {{ Form::fileGroup('picture',  trans_choice('general.pictures', 1)) }}
        </div>
        <!-- /.box-body -->

        @permission(['update-customers-profile'])
        <div class="box-footer">
            {{ Form::saveButtons('customers') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $("#locale").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.languages', 1)]) }}"
            });

            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($user->picture)
                placeholder : '<?php echo $user->picture->basename; ?>'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($user->picture)
                picture_html  = '<span class="picture">';
                picture_html += '    <a href="{{ url('uploads/' . $user->picture->id . '/download') }}">';
                picture_html += '        <span id="download-picture" class="text-primary">';
                picture_html += '            <i class="fa fa-file-{{ $user->picture->aggregate_type }}-o"></i> {{ $user->picture->basename }}';
                picture_html += '        </span>';
                picture_html += '    </a>';
                picture_html += '    {!! Form::open(['id' => 'picture-' . $user->picture->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $user->picture->id)], 'style' => 'display:inline']) !!}';
                picture_html += '    <a id="remove-picture" href="javascript:void();">';
                picture_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
                picture_html += '    </a>';
                picture_html += '    {!! Form::close() !!}';
                picture_html += '</span>';

                $('.fancy-file .fake-file').append(picture_html);

                $(document).on('click', '#remove-picture', function (e) {
                    confirmDelete("#picture-{!! $user->picture->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $user->picture->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
                });
            @endif
        });
    </script>
@endpush
