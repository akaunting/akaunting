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

            $('#tax_id').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                language: {
                    noResults: function () {
                        return '<span id="tax-add-new"><i class="fa fa-plus"></i> {{ trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]) }}</span>';
                    }
                }
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                @if($item->picture)
                placeholder : '{{ $item->picture->basename }}'
                @else
                placeholder : '{{ trans('general.form.no_file_selected') }}'
                @endif
            });

            @if($item->picture)
            $.ajax({
                url: '{{ url('uploads/' . $item->picture->id . '/show') }}',
                type: 'GET',
                data: {column_name: 'picture'},
                dataType: 'JSON',
                success: function(json) {
                    if (json['success']) {
                        $('.fancy-file').after(json['html']);
                    }
                }
            });

            @permission('delete-common-uploads')
            $(document).on('click', '#remove-picture', function (e) {
                confirmDelete("#picture-{!! $item->picture->id !!}", "{!! trans('general.attachment') !!}", "{!! trans('general.delete_confirm', ['name' => '<strong>' . $item->picture->basename . '</strong>', 'type' => strtolower(trans('general.attachment'))]) !!}", "{!! trans('general.cancel') !!}", "{!! trans('general.delete')  !!}");
            });
            @endpermission
            @endif
        });

        $(document).on('click', '.select2-results__option.select2-results__message', function(e) {
            tax_name = $('.select2-search__field').val();

            $('body > .select2-container.select2-container--default.select2-container--open').remove();

            $('#modal-create-tax').remove();

            $.ajax({
                url: '{{ url("modals/taxes/create") }}',
                type: 'GET',
                dataType: 'JSON',
                data: {name: tax_name},
                success: function(json) {
                    if (json['success']) {
                        $('body').append(json['html']);
                    }
                }
            });
        });

        $(document).on('hidden.bs.modal', '#modal-create-tax', function () {
            $('#tax_id').select2({
                placeholder: {
                    id: '-1', // the value of the option
                    text: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                language: {
                    noResults: function () {
                        return '<span id="tax-add-new"><i class="fa fa-plus-circle"></i> {{ trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]) }}</span>';
                    }
                }
            });
        });
    </script>
@endpush
