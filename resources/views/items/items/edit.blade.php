@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.items', 1)]))

@section('content')
<!-- Default box -->
<div class="box box-success">
    {!! Form::model($item, [
        'method' => 'PATCH',
        'files' => true,
        'url' => ['items/items', $item->id],
        'role' => 'form'
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

    @permission('update-items-items')
    <div class="box-footer">
        {{ Form::saveButtons('items/items') }}
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
            $("#tax_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '<?php echo $item->picture; ?>'
            });
        });
    </script>
@endpush
