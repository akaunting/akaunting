<div id="widget-{{ $model->id }}" class="{{ $model->settings->width }}">
    <div class="card">
        @include('partials.widgets.standard_header')

        <div class="card-body" id="widget-donut-{{ $model->id }}">
            <div class="chart-donut">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var widget_donut_{{ $model->id }} = new Vue({
            el: '#widget-donut-{{ $model->id }}',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
