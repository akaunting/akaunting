<div class="card-header">
    {!! Form::open([
        'url' => 'common/reports/' . $class->report->id . '/display',
        'role' => 'form',
        'method' => 'GET',
    ]) !!}

    <div id="items" class="float-left">
        {!! Form::select('year', $class->filters['years'], request('year', $class->year), ['class' => 'form-control form-control-sm table-header-search']) !!}
        @php unset($class->filters['years']) @endphp
        @foreach($class->filters as $name => $values)
            {!! Form::select($name . '[]', $values, request($name), ['id' => 'filter-' . $name, 'class' => 'form-control form-control-sm table-header-search']) !!}
        @endforeach
        {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-secondary btn-sm card-buttons']) !!}
    </div>

    {!! Form::close() !!}
</div>
