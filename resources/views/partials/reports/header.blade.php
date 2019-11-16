@section('title', $class->report->name)

@section('new_button')
    <span class="new-button">
        <a href="{{ url($class->getUrl('print')) }}" target="_blank" class="btn btn-white btn-sm"><span class="fa fa-print"></span> &nbsp;{{ trans('general.print') }}</a>
    </span>
    <span>
        <a href="{{ url($class->getUrl('export')) }}" class="btn btn-white btn-sm"><span class="fa fa-upload"></span> &nbsp;{{ trans('general.export') }}</a>
    </span>
@endsection
