@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.vendors', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($vendor, [
            'method' => 'PATCH',
            'files' => true,
            'url' => ['expenses/vendors', $vendor->id],
            'role' => 'form'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

            {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

            {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies) }}

            {{ Form::textGroup('phone', trans('general.phone'), 'phone', []) }}

            {{ Form::textGroup('website', trans('general.website'), 'globe',[]) }}

            {{ Form::textareaGroup('address', trans('general.address')) }}

            {{ Form::fileGroup('logo', trans_choice('general.logos', 1)) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-expenses-vendors')
        <div class="box-footer">
            {{ Form::saveButtons('expenses/vendors') }}
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
            $("#currency_code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
            });

            $('#logo').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($vendor->logo)
                placeholder : '<?php echo $vendor->logo->basename; ?>'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($vendor->logo)
            logo_html  = '<span class="logo">';
            logo_html += '    <a href="{{ url('uploads/' . $vendor->logo->id . '/download') }}">';
            logo_html += '        <span id="download-logo" class="text-primary">';
            logo_html += '            <i class="fa fa-file-{{ $vendor->logo->aggregate_type }}-o"></i> {{ $vendor->logo->basename }}';
            logo_html += '        </span>';
            logo_html += '    </a>';
            logo_html += '    {!! Form::open(['id' => 'logo-' . $vendor->logo->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $vendor->logo->id)], 'style' => 'display:inline']) !!}';
            logo_html += '    <a id="remove-logo" href="javascript:void();">';
            logo_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
            logo_html += '    </a>';
            logo_html += '    {!! Form::close() !!}';
            logo_html += '</span>';

            $('.fancy-file .fake-file').append(logo_html);

            $(document).on('click', '#remove-logo', function (e) {
                confirmDelete("#logo-{!! $vendor->logo->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $vendor->logo->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endif
        });
    </script>
@endpush
