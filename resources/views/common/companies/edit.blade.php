@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.companies', 1)]))

@section('content')
        <!-- Default box -->
        <div class="box box-success">
            {!! Form::model($company, [
                'method' => 'PATCH',
                'url' => ['common/companies', $company->id],
                'files' => true,
                'role' => 'form'
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
                placeholder : '<?php echo $company->company_logo->basename; ?>'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($company->company_logo)
                attachment_html  = '<span class="attachment">';
                attachment_html += '    <a href="{{ url('uploads/' . $company->company_logo->id . '/download') }}">';
                attachment_html += '        <span id="download-attachment" class="text-primary">';
                attachment_html += '            <i class="fa fa-file-{{ $company->company_logo->aggregate_type }}-o"></i> {{ $company->company_logo->basename }}';
                attachment_html += '        </span>';
                attachment_html += '    </a>';
                attachment_html += '    {!! Form::open(['id' => 'attachment-' . $company->company_logo->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $company->company_logo->id)], 'style' => 'display:inline']) !!}';
                attachment_html += '    <a id="remove-attachment" href="javascript:void();">';
                attachment_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
                attachment_html += '    </a>';
                attachment_html += '    {!! Form::close() !!}';
                attachment_html += '</span>';

                $('.fancy-file .fake-file').append(attachment_html);

                $(document).on('click', '#remove-attachment', function (e) {
                    confirmDelete("#attachment-{!! $company->company_logo->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $company->company_logo->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
                });
            @endif
        });
    </script>
@endpush
