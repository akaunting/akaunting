<div class="modal fade create-category-{{ $rand }}" id="modal-create-category" style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['id' => 'form-create-category', 'role' => 'form', 'class' => 'form-loading-button']) !!}

                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

                    @stack('color_input_start')
                    <div class="form-group col-md-6 required {{ $errors->has('color') ? 'has-error' : ''}}">
                        {!! Form::label('color', trans('general.color'), ['class' => 'control-label']) !!}
                        <div  id="category-color-picker" class="input-group colorpicker-component">
                            <div class="input-group-addon"><i></i></div>
                            {!! Form::text('color', '#00a65a', ['id' => 'color', 'class' => 'form-control', 'required' => 'required']) !!}
                        </div>
                        {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
                    </div>
                    @stack('color_input_end')

                    {!! Form::hidden('type', $type, []) !!}
                    {!! Form::hidden('enabled', '1', []) !!}
                </div>

                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <div class="pull-left">
                    {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-category', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}

                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.create-category-{{ $rand }}#modal-create-category').modal('show');

        $('.create-category-{{ $rand }} #category-color-picker').colorpicker();
    });

    $(document).on('click', '.create-category-{{ $rand }} #button-create-category', function (e) {
        $('.create-category-{{ $rand }}#modal-create-category .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 10em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

        $.ajax({
            url: '{{ url("modals/categories") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $(".create-category-{{ $rand }} #form-create-category").serialize(),
            beforeSend: function () {
                $('.create-category-{{ $rand }} #button-create-category').button('loading');

                $(".create-category-{{ $rand }} .form-group").removeClass("has-error");
                $(".create-category-{{ $rand }} .help-block").remove();
            },
            complete: function() {
                $('.create-category-{{ $rand }} #button-create-category').button('reset');
            },
            success: function(json) {
                var data = json['data'];

                $('.create-category-{{ $rand }} #span-loading').remove();

                $('.create-category-{{ $rand }}#modal-create-category').modal('hide');

                $('#category_id').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('#category_id').trigger('change');
                $('#category_id').select2('refresh');

                @if ($category_selector)
                $('{{ $category_selector }}').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('{{ $category_selector }}').trigger('change');
                $('{{ $category_selector }}').select2('refresh');
                @endif
            },
            error: function(error, textStatus, errorThrown) {
                $('.create-category-{{ $rand }} #span-loading').remove();

                if (error.responseJSON.name) {
                    $(".create-category-{{ $rand }}#modal-create-category input[name='name']").parent().parent().addClass('has-error');
                    $(".create-category-{{ $rand }}#modal-create-category input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                }

                if (error.responseJSON.color) {
                    $(".create-category-{{ $rand }}#modal-create-category input[name='color']").parent().parent().addClass('has-error');
                    $(".create-category-{{ $rand }}#modal-create-category input[name='color']").parent().after('<p class="help-block">' + error.responseJSON.color + '</p>');
                }
            }
        });
    });
</script>
