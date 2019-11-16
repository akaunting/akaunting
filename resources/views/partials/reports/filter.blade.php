<div class="card-header">
    {!! Form::open([
        'url' => 'common/reports/' . $class->report->id . '/display',
        'role' => 'form',
        'method' => 'GET',
        'class' => 'mb-0',
    ]) !!}

    <div id="items" class="float-left box-filter">
        {!! Form::select('year', $class->filters['years'], request('year', $class->year), ['class' => 'form-control form-control-sm table-header-search ml--2']) !!}
        @php unset($class->filters['years']) @endphp
        @foreach($class->filters as $name => $values)
        {!! Form::select($name . '[]', $values, request($name), ['id' => 'filter-' . $name, 'class' => 'form-control form-control-sm table-header-search']) !!}
        @endforeach
        {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-secondary btn-sm card-buttons ml-1']) !!}
    </div>

    {!! Form::close() !!}
</div>
