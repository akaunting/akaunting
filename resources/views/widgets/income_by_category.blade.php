<div id="widgets-income-by-category" class="{{ $model->settings->width }}">
    <div class="card">
        @include('partials.widgets.standard_header', ['header_class' => 'border-bottom-1'])

        <div class="card-body" id="income-category-doughnut">
            <div class="dashboard-categories">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
</div>

@push('charts')
    <script>
        var income_category_doughnut = new Vue({
            el: '#income-category-doughnut',
        });
    </script>
@endpush

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
