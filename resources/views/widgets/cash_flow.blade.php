<div id="widgets-cash-flow" class="{{ $model->settings->width }}">
    <div class="card">
        @include('partials.widgets.standard_header')

        <div class="card-body" id="cashflow">
            <div class="chart">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var cash_flow = new Vue({
            el: '#cashflow',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
