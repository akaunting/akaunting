@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.items', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['url' => 'items/items', 'files' => true, 'role' => 'form']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('sku', trans('items.sku'), 'key') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::numberGroup('sale_price', trans('items.sales_price'), 'money') }}

            {{ Form::numberGroup('purchase_price', trans('items.purchase_price'), 'money') }}

            {{ Form::textGroup('quantity', trans_choice('items.quantities', 1), 'cubes', ['required' => 'required'], '1') }}

            {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percent', $taxes, setting('general.default_tax'), []) }}

            {{ Form::selectGroup('category_id', trans_choice('general.categories', 1), 'folder-open-o', $categories, null, []) }}

            {{ Form::fileGroup('picture', trans_choice('general.pictures', 1)) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('items/items') }}
        </div>
        <!-- /.box-footer -->

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
            $('#enabled_1').trigger('click');

            $('#name').focus();

            $("#tax_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.taxes', 1)]) }}"
            });

            $("#category_id").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)]) }}"
            });

            $('#picture').fancyfile({
                text  : '{{ trans('general.form.select.file') }}',
                style : 'btn-default',
                placeholder : '{{ trans('general.form.no_file_selected') }}'
            });
        });
    </script>
@endpush
