<div class="card-header">
    {!! Form::open([
        'method' => 'GET',
        'route' => ['reports.show', $class->model->id],
        'role' => 'form',
    ]) !!}

        <div id="items" class="float-left">
            @if(isset($class->filters['years']))
            {!! Form::select('year', $class->filters['years'], request('year', $class->year), ['class' => 'form-control form-control-sm d-inline-block w-auto']) !!}
            @php unset($class->filters['years']) @endphp
            @endif
            @foreach($class->filters as $name => $values)
                {!! Form::select($name . '[]', $values, request($name), ['id' => 'filter-' . $name, 'class' => 'form-control form-control-sm d-inline-block w-auto']) !!}
            @endforeach
            {!! Form::button('<span class="fa fa-filter"></span> &nbsp;' . trans('general.filter'), ['type' => 'submit', 'class' => 'btn btn-sm btn-secondary']) !!}
        </div>

    {!! Form::close() !!}
</div>
