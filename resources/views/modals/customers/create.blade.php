<div class="modal fade" id="modal-create-customer" style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.customers', 1)]) }}</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['id' => 'form-create-customer', 'role' => 'form', 'class' => 'form-loading-button']) !!}
                <div class="row">
                    {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

                    {{ Form::textGroup('email', trans('general.email'), 'envelope', []) }}

                    {{ Form::textGroup('tax_number', trans('general.tax_number'), 'percent', []) }}

                    {{ Form::selectGroup('currency_code', trans_choice('general.currencies', 1), 'exchange', $currencies, setting('general.default_currency')) }}

                    {{ Form::textareaGroup('address', trans('general.address')) }}

                    {!! Form::hidden('enabled', '1', []) !!}
                </div>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <div class="pull-left">
                    {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-customer', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#modal-create-customer').modal('show');

        $("#modal-create-customer #currency_code").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
        });
    });

    $(document).on('click', '#button-create-customer', function (e) {
        $('#modal-create-customer .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

        $.ajax({
            url: '{{ url("modals/customers") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $("#form-create-customer").serialize(),
            beforeSend: function () {
                $('#button-create-customer').button('loading');

                $(".form-group").removeClass("has-error");
                $(".help-block").remove();
            },
            complete: function() {
                $('#button-create-customer').button('reset');
            },
            success: function(json) {
                var data = json['data'];

                $('#span-loading').remove();

                $('#modal-create-customer').modal('hide');

                $("#customer_id").append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('#customer_id').trigger('change');
                $("#customer_id").select2('refresh');
            },
            error: function(error, textStatus, errorThrown) {
                $('#span-loading').remove();

                if (error.responseJSON.name) {
                    $("#modal-create-customer input[name='name']").parent().parent().addClass('has-error');
                    $("#modal-create-customer input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                }

                if (error.responseJSON.email) {
                    $("#modal-create-customer input[name='email']").parent().parent().addClass('has-error');
                    $("#modal-create-customer input[name='email']").parent().after('<p class="help-block">' + error.responseJSON.email + '</p>');
                }

                if (error.responseJSON.currency_code) {
                    $("#modal-create-customer select[name='currency_code']").parent().parent().addClass('has-error');
                    $("#modal-create-customer select[name='currency_code']").parent().after('<p class="help-block">' + error.responseJSON.currency_code + '</p>');
                }
            }
        });
    });
</script>