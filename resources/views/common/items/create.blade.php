@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.items', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::open(['route' => 'items.store', 'files' => true, 'role' => 'form']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('sku', trans('items.sku'), 'key') }}

            {{ Form::textareaGroup('description', trans('general.description')) }}

            {{ Form::textGroup('sale_price', trans('items.sales_price'), 'money') }}

            {{ Form::textGroup('purchase_price', trans('items.purchase_price'), 'money') }}

            {{ Form::textGroup('quantity', trans_choice('items.quantities', 1), 'cubes', ['required' => 'required'], '1') }}

            {{ Form::selectGroup('tax_id', trans_choice('general.taxes', 1), 'percent', $taxes, setting('general.default_tax'), []) }}

            <div class="form-group col-md-6 required {{ $errors->has('category_id') ? 'has-error' : ''}}">
                {!! Form::label('category_id', trans_choice('general.categories', 1), ['class' => 'control-label']) !!}
                <div class="input-group">
                    <div class="input-group-addon"><i class="fa fa-folder-open-o"></i></div>
                    {!! Form::select('category_id', $categories, null, array_merge(['class' => 'form-control', 'placeholder' => trans('general.form.select.field', ['field' => trans_choice('general.categories', 1)])])) !!}
                    <div class="input-group-btn">
                        <button type="button" onclick="createCategory();" class="btn btn-default btn-icon"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                {!! $errors->first('category_id', '<p class="help-block">:message</p>') !!}
            </div>

            {{ Form::fileGroup('picture', trans_choice('general.pictures', 1)) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('common/items') }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
    </div>
@endsection

@push('js')
    <script src="{{ asset('public/js/bootstrap-fancyfile.js') }}"></script>
    <script src="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.js') }}"></script>
@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap-fancyfile.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/almasaeed2010/adminlte/plugins/colorpicker/bootstrap-colorpicker.css') }}">
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

        function createCategory() {
            $('#modal-create-category').remove();

            modal  = '<div class="modal fade" id="modal-create-category" style="display: none;">';
            modal += '  <div class="modal-dialog  modal-lg">';
            modal += '      <div class="modal-content">';
            modal += '          <div class="modal-header">';
            modal += '              <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}</h4>';
            modal += '          </div>';
            modal += '          <div class="modal-body">';
            modal += '              {!! Form::open(['id' => 'form-create-category', 'role' => 'form']) !!}';
            modal += '              <div class="row">';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="name" class="control-label">{{ trans('general.name') }}</label>';
            modal += '                      <div class="input-group">';
            modal += '                          <div class="input-group-addon"><i class="fa fa-id-card-o"></i></div>';
            modal += '                          <input class="form-control" placeholder="{{ trans('general.name') }}" required="required" name="name" type="text" id="name">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  <div class="form-group col-md-6 required">';
            modal += '                      <label for="color" class="control-label">{{ trans('general.color') }}</label>';
            modal += '                      <div  id="category-color-picker" class="input-group colorpicker-component">';
            modal += '                          <div class="input-group-addon"><i></i></div>';
            modal += '                          <input class="form-control" value="#00a65a" placeholder="{{ trans('general.color') }}" required="required" name="color" type="text" id="color">';
            modal += '                      </div>';
            modal += '                  </div>';
            modal += '                  {!! Form::hidden('type', 'item', []) !!}';
            modal += '                  {!! Form::hidden('enabled', '1', []) !!}';
            modal += '              </div>';
            modal += '              {!! Form::close() !!}';
            modal += '          </div>';
            modal += '          <div class="modal-footer">';
            modal += '              <div class="pull-left">';
            modal += '              {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-category', 'class' => 'btn btn-success']) !!}';
            modal += '              <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>';
            modal += '              </div>';
            modal += '          </div>';
            modal += '      </div>';
            modal += '  </div>';
            modal += '</div>';

            $('body').append(modal);

            $('#category-color-picker').colorpicker();

            $('#modal-create-category').modal('show');
        }

        $(document).on('click', '#button-create-category', function (e) {
            $('#modal-create-category .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 10em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

            $.ajax({
                url: '{{ url("settings/categories/category") }}',
                type: 'POST',
                dataType: 'JSON',
                data: $("#form-create-category").serialize(),
                beforeSend: function () {
                    $(".form-group").removeClass("has-error");
                    $(".help-block").remove();
                },
                success: function(data) {
                    $('#span-loading').remove();

                    $('#modal-create-category').modal('hide');

                    $("#category_id").append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                    $("#category_id").select2('refresh');
                },
                error: function(error, textStatus, errorThrown) {
                    $('#span-loading').remove();

                    if (error.responseJSON.name) {
                        $("input[name='name']").parent().parent().addClass('has-error');
                        $("input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                    }

                    if (error.responseJSON.color) {
                        $("input[name='color']").parent().parent().addClass('has-error');
                        $("input[name='color']").parent().after('<p class="help-block">' + error.responseJSON.color + '</p>');
                    }
                }
            });
        });
    </script>
@endpush
