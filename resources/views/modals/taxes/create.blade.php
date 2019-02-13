<div class="modal fade create-tax-{{ $rand }}" id="modal-create-tax" style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]) }}</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['id' => 'form-create-tax', 'role' => 'form', 'class' => 'form-loading-button']) !!}

                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

                    {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

                    {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, 'normal') }}

                    {!! Form::hidden('enabled', '1', []) !!}
                </div>

                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <div class="pull-left">
                    {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-tax', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}

                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.create-tax-{{ $rand }}#modal-create-tax').modal('show');

        $('.create-tax-{{ $rand }} #rate').focus();

        $(".create-tax-{{ $rand }} #type").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]) }}"
        });
    });

    $(document).on('click', '.create-tax-{{ $rand }} #button-create-tax', function (e) {
        $('.create-tax-{{ $rand }}#modal-create-tax .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

        $.ajax({
            url: '{{ url("modals/taxes") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $(".create-tax-{{ $rand }} #form-create-tax").serialize(),
            beforeSend: function () {
                $('.create-tax-{{ $rand }} #button-create-tax').button('loading');

                $(".create-tax-{{ $rand }} .form-group").removeClass("has-error");
                $(".create-tax-{{ $rand }} .help-block").remove();
            },
            complete: function() {
                $('.create-tax-{{ $rand }} #button-create-tax').button('reset');
            },
            success: function(json) {
                var data = json['data'];

                $('.create-tax-{{ $rand }} #span-loading').remove();

                $('.create-tax-{{ $rand }}#modal-create-tax').modal('hide');

                $('#tax_id').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('#tax_id').trigger('change');
                $('#tax_id').select2('refresh');

                @if ($tax_selector)
                $('{{ $tax_selector }}').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('{{ $tax_selector }}').trigger('change');
                $('{{ $tax_selector }}').select2('refresh');
                @endif
            },
            error: function(error, textStatus, errorThrown) {
                $('.create-tax-{{ $rand }} #span-loading').remove();

                if (error.responseJSON.name) {
                    $(".create-tax-{{ $rand }}#modal-create-tax input[name='name']").parent().parent().addClass('has-error');
                    $(".create-tax-{{ $rand }}#modal-create-tax input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                }

                if (error.responseJSON.rate) {
                    $(".create-tax-{{ $rand }}#modal-create-tax input[name='rate']").parent().parent().addClass('has-error');
                    $(".create-tax-{{ $rand }}#modal-create-tax input[name='rate']").parent().after('<p class="help-block">' + error.responseJSON.rate + '</p>');
                }
            }
        });
    });
</script>
