<div class="modal fade create-vendor-{{ $rand }}" id="modal-create-vendor" style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ trans('general.title.new', ['type' => trans_choice('general.vendors', 1)]) }}</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['id' => 'form-create-vendor', 'role' => 'form', 'class' => 'form-loading-button']) !!}

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
                    {!! Form::button('<span class="fa fa-save"></span> &nbsp;' . trans('general.save'), ['type' => 'button', 'id' =>'button-create-vendor', 'class' => 'btn btn-success button-submit', 'data-loading-text' => trans('general.loading')]) !!}

                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-times-circle"></span> &nbsp;{{ trans('general.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.create-vendor-{{ $rand }}#modal-create-vendor').modal('show');

        $(".create-vendor-{{ $rand }}#modal-create-vendor #currency_code").select2({
            placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.currencies', 1)]) }}"
        });
    });

    $(document).on('click', '.create-vendor-{{ $rand }} #button-create-vendor', function (e) {
        $('.create-vendor-{{ $rand }}#modal-create-vendor .modal-header').before('<span id="span-loading" style="position: absolute; height: 100%; width: 100%; z-index: 99; background: #6da252; opacity: 0.4;"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;margin-left: 35%;margin-top: 8%;"></i></span>');

        $.ajax({
            url: '{{ url("modals/vendors") }}',
            type: 'POST',
            dataType: 'JSON',
            data: $(".create-vendor-{{ $rand }} #form-create-vendor").serialize(),
            beforeSend: function () {
                $('.create-vendor-{{ $rand }} #button-create-vendor').button('loading');

                $(".create-vendor-{{ $rand }} .form-group").removeClass("has-error");
                $(".create-vendor-{{ $rand }} .help-block").remove();
            },
            complete: function() {
                $('.create-vendor-{{ $rand }} #button-create-vendor').button('reset');
            },
            success: function(json) {
                var data = json['data'];

                $('.create-vendor-{{ $rand }} #span-loading').remove();

                $('.create-vendor-{{ $rand }}#modal-create-vendor').modal('hide');

                $('#vendor_id').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('#vendor_id').trigger('change');
                $('#vendor_id').select2('refresh');

                @if ($vendor_selector)
                $('{{ $vendor_selector }}').append('<option value="' + data.id + '" selected="selected">' + data.name + '</option>');
                $('{{ $vendor_selector }}').trigger('change');
                $('{{ $vendor_selector }}').select2('refresh');
                @endif
            },
            error: function(error, textStatus, errorThrown) {
                $('.create-vendor-{{ $rand }} #span-loading').remove();

                if (error.responseJSON.name) {
                    $(".create-vendor-{{ $rand }}#modal-create-vendor input[name='name']").parent().parent().addClass('has-error');
                    $(".create-vendor-{{ $rand }}#modal-create-vendor input[name='name']").parent().after('<p class="help-block">' + error.responseJSON.name + '</p>');
                }

                if (error.responseJSON.email) {
                    $(".create-vendor-{{ $rand }}#modal-create-vendor input[name='email']").parent().parent().addClass('has-error');
                    $(".create-vendor-{{ $rand }}#modal-create-vendor input[name='email']").parent().after('<p class="help-block">' + error.responseJSON.email + '</p>');
                }

                if (error.responseJSON.currency_code) {
                    $(".create-vendor-{{ $rand }}#modal-create-vendor select[name='currency_code']").parent().parent().addClass('has-error');
                    $(".create-vendor-{{ $rand }}#modal-create-vendor select[name='currency_code']").parent().after('<p class="help-block">' + error.responseJSON.currency_code + '</p>');
                }
            }
        });
    });
</script>
