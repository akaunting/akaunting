@section('title', $class->model->name)

@section('new_button')
    <a href="{{ url($class->getUrl('print')) }}" target="_blank" class="btn btn-white btn-sm">
        {{ trans('general.print') }}
    </a>
    <a href="{{ url($class->getUrl('export')) }}" class="btn btn-white btn-sm">
        {{ trans('general.export') }}
    </a>
@endsection
