@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.currencies', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($currency, [
            'method' => 'PATCH',
            'url' => ['settings/currencies', $currency->id],
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes) }}

            {{ Form::textGroup('rate', trans('currencies.rate'), 'money') }}

            {{ Form::numberGroup('precision', trans('currencies.precision'), 'bullseye') }}

            {{ Form::textGroup('symbol', trans('currencies.symbol.symbol'), 'font') }}

            {{ Form::selectGroup('symbol_first', trans('currencies.symbol.position'), 'text-width', ['1' => trans('currencies.symbol.before'), '0' => trans('currencies.symbol.after')]) }}

            {{ Form::textGroup('decimal_mark', trans('currencies.decimal_mark'), 'columns') }}

            {{ Form::textGroup('thousands_separator', trans('currencies.thousands_separator'), 'columns', []) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}

            {{ Form::radioGroup('default_currency', trans('currencies.default')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-settings-currencies')
        <div class="box-footer">
            {{ Form::saveButtons('settings/currencies') }}
        </div>
        <!-- /.box-footer -->
        @endpermission

        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function(){
            $("#code").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans('currencies.code')]) }}"
            });

            $('#code').change(function() {
                $.ajax({
                    url: '{{ url("settings/currencies/config") }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: 'code=' + $(this).val(),
                    success: function(data) {
                        $('#precision').val(data.precision);
                        $('#symbol').val(data.symbol);
                        $('#symbol_first').val(data.symbol_first);
                        $('#decimal_mark').val(data.decimal_mark);
                        $('#thousands_separator').val(data.thousands_separator);

                        // This event Select2 Stylesheet
                        $('#symbol_first').trigger('change');
                    }
                });
            });
        });
    </script>
@endpush
