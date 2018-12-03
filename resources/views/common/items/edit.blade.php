@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.items', 1)]))

@section('content')
<!-- Default box -->
<div class="box box-success">
    {!! Form::model($item, [
        'method' => 'PATCH',
        'files' => true,
        'route' => ['items.update', $item->id],
        'role' => 'form',
        'class' => 'form-loading-button'
    ]) !!}

    <div class="box-body">
        {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

        {{ Form::textGroup('sku', trans('items.sku'), 'key') }}

        {{ Form::textareaGroup('description', trans('general.description')) }}

        {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money') }}

        {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money') }}

        {{ Form::textGroup('quantity', trans_choice('items.quantities', 1), 'cubes') }}

        {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percent', $taxes, null, []) }}

        {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder-open-o', $categories, null, []) }}

        {{ Form::fileGroup('picture', trans_choice('general.pictures', 1)) }}

        {{ Form::radioGroup('enabled', trans('general.enabled')) }}
    </div>
    <!-- /.box-body -->

    @permission('update-common-items')
    <div class="box-footer">
        {{ Form::saveButtons('common/items') }}
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
            /*$("#sale_price").maskMoney({
                thousands : '{{ $currency->thousands_separator }}',
                decimal : '{{ $currency->decimal_mark }}',
                precision : {{ $currency->precision }},
                allowZero : true,
                @if($currency->symbol_first)
                prefix : '{{ $currency->symbol }}'
                @else
                suffix : '{{ $currency->symbol }}'
                @endif
            });

            $("#purchase_price").maskMoney({
                thousands : '{{ $currency->thousands_separator }}',
                decimal : '{{ $currency->decimal_mark }}',
                precision : {{ $currency->precision }},
                allowZero : true,
                @if($currency->symbol_first)
                prefix : '{{ $currency->symbol }}'
                @else
                suffix : '{{ $currency->symbol }}'
                @endif
            });

            $("#sale_price").focusout();
            $("#purchase_price").focusout();*/

            $("#tax_id").select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                }
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($item->picture)
                placeholder : '<?php echo $item->picture->basename; ?>'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($item->picture)
                picture_html  = '<span class="picture">';
                picture_html += '    <a href="{{ url('uploads/' . $item->picture->id . '/download') }}">';
                picture_html += '        <span id="download-picture" class="text-primary">';
                picture_html += '            <i class="fa fa-file-{{ $item->picture->aggregate_type }}-o"></i> {{ $item->picture->basename }}';
                picture_html += '        </span>';
                picture_html += '    </a>';
                picture_html += '    {!! Form::open(['id' => 'picture-' . $item->picture->id, 'method' => 'DELETE', 'url' => [url('uploads/' . $item->picture->id)], 'style' => 'display:inline']) !!}';
                picture_html += '    <a id="remove-picture" href="javascript:void();">';
                picture_html += '        <span class="text-danger"><i class="fa fa fa-times"></i></span>';
                picture_html += '    </a>';
                picture_html += '    {!! Form::close() !!}';
                picture_html += '</span>';
    
                $('.fancy-file .fake-file').append(picture_html);
    
                $(document).on('click', '#remove-picture', function (e) {
                    confirmDelete("#picture-{!! $item->picture->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $item->picture->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
                });
            @endif
        });
    </script>
@endpush
