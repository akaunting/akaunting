<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }}">
    <div class="card">
        @include($class->views['header'])

        <div class="card-body" id="widget-donut-{{ $class->model->id }}">
            <div class="chart-donut">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
