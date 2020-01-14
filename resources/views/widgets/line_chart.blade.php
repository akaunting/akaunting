<div id="widget-{{ $model->id }}" class="{{ $model->settings->width }}">
    <div class="card">
        @include('partials.widgets.standard_header')

        <div class="card-body pt-0" id="widget-line-{{ $model->id }}">
            <div class="chart">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var widget_line_{{ $model->id }} = new Vue({
            el: '#widget-line-{{ $model->id }}',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
