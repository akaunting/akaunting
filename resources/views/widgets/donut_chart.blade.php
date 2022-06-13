<div id="widget-{{ $class->model->id }}" class="apexcharts-donut-custom {{ $class->model->settings->width }} my-8">
    @include($class->views['header'], ['header_class' => ''])

    <div class="flex flex-col lg:flex-row mt-3" id="widget-donut-{{ $class->model->id }}">
        <div class="w-full">
            {!! $chart->container() !!}
        </div>
    </div>
</div>

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
