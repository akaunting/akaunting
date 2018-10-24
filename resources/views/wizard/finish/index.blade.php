@extends('layouts.wizard')

@section('title', trans('general.wizard'))

@section('content')
<!-- Default box -->
<div class="box box-solid">
    <div class="box-body">
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/companies') }}" type="button" class="btn btn-default btn-circle">1</a>
                    <p><small>{{ trans_choice('general.companies', 1) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/currencies') }}" type="button" class="btn btn-default btn-circle">2</a>
                    <p><small>{{ trans_choice('general.currencies', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="{{ url('wizard/taxes') }}" type="button" class="btn btn-default btn-circle">3</a>
                    <p><small>{{ trans_choice('general.taxes', 2) }}</small></p>
                </div>
                <div class="stepwizard-step col-xs-3">
                    <a href="#step-4" type="button" class="btn btn-success btn-circle">4</a>
                    <p><small>{{ trans_choice('general.finish', 1) }}</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-success">

</div>
@endsection

@push('scripts')
<script type="text/javascript">
    var text_yes = '{{ trans('general.yes') }}';
    var text_no = '{{ trans('general.no') }}';

    $(document).ready(function() {

    });
</script>
@endpush
