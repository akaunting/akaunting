@extends('layouts.admin')

@section('title', trans('general.title.edit', ['type' => trans_choice('general.tax_rates', 1)]))

@section('content')
    <!-- Default box -->
    <div class="box box-success">
        {!! Form::model($tax, [
            'method' => 'PATCH',
            'url' => ['settings/taxes', $tax->id],
            'role' => 'form',
            'class' => 'form-loading-button'
        ]) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

            {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, null) }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        @permission('update-settings-taxes')
        <div class="box-footer">
            {{ Form::saveButtons('settings/taxes') }}
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

        $(document).ready(function() {
            $('#name').focus();

            $("#type").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]) }}"
            });
        });
    </script>
@endpush
