@extends('layouts.admin')

@section('title', trans('general.title.new', ['type' => trans_choice('general.tax_rates', 1)]))

@section('content')
      <!-- Default box -->
      <div class="box box-success">
        {!! Form::open(['url' => 'settings/taxes', 'role' => 'form', 'class' => 'form-loading-button']) !!}

        <div class="box-body">
            {{ Form::textGroup('name', trans('general.name'), 'id-card-o') }}

            {{ Form::textGroup('rate', trans('taxes.rate'), 'percent') }}

            {{ Form::selectGroup('type', trans_choice('general.types', 1), 'bars', $types, 'normal') }}

            {{ Form::radioGroup('enabled', trans('general.enabled')) }}
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{ Form::saveButtons('settings/taxes') }}
        </div>
        <!-- /.box-footer -->

        {!! Form::close() !!}
      </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var text_yes = '{{ trans('general.yes') }}';
        var text_no = '{{ trans('general.no') }}';

        $(document).ready(function() {
            $('#enabled_1').trigger('click');

            $('#name').focus();

            $("#type").select2({
                placeholder: "{{ trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]) }}"
            });
        });
    </script>
@endpush
