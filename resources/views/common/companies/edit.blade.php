@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.companies', 1)]))

@section('content')
        <!-- Default box -->
        <div class="box box-success">
            {!! Form::model($company, [
                'method' => 'PATCH',
                'url' => ['common/companies', $company->id],
                'files' => true,
                'role' => 'form',
                'class' => 'form-loading-button'
            ]) !!}

            <div class="box-body">
                {{ Form::textGroup('company_name', trans('general.name'), 'id-card-o') }}

                {{ Form::textGroup('domain', trans('companies.domain'), 'globe') }}

                {{ Form::emailGroup('company_email', trans('general.email'), 'envelope') }}

                {{ Form::selectGroup('default_currency', trans_choice('general.currencies', 1), 'money', $currencies) }}

                {{ Form::textareaGroup('company_address', trans('general.address')) }}

                {{ Form::fileGroup('company_logo', trans('companies.logo')) }}

                {{ Form::radioGroup('enabled', trans('general.enabled')) }}
            </div>
            <!-- /.box-body -->

            @permission('update-common-companies')
            <div class="box-footer">
                {{ Form::saveButtons('common/companies') }}
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
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function(){
            $("#default_currency").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $('#company_logo').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($company->company_logo)
                placeholder : '{{ $company->company_logo->basename }}'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($company->company_logo)
            $.ajax({
                url: '{{ url('uploads/' . $company->company_logo->id . '/show') }}',
                type: 'GET',
                data: {column_name: 'attachment'},
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('.fancy-file').after(json['html']);
                    }
                }
            });

            @permission('delete-common-uploads')
            $(document).on('click', '#remove-attachment', function (e) {
                confirmDelete("#attachment-{!! $company->company_logo->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $company->company_logo->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endpermission
            @endif
        });
    </script>
@endpush
