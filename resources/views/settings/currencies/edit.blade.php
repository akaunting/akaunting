@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.currencies', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($currency, [
            'method' => 'PATCH',
            'url' => ['settings/currencies', $currency->id],
            'role' => 'form'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}

            {{ Form::selectGroup('code', trans('currencies.code'), 'code', $codes, setting('currencies.code')) }}

            {{ Form::radioGroup('default_currency', trans('currencies.default')) }}

            {{ Form::textGroup('rate', trans('currencies.rate'), 'money') }}
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
        });
    </script>
@endpush
