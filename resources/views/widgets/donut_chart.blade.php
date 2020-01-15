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

@push('charts')
    <script>
        var widget_donut_{{ $class->model->id }} = new Vue({
            el: '#widget-donut-{{ $class->model->id }}',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
