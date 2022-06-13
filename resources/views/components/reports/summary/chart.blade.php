@php $charts = $class->getCharts($table_key); @endphp

<div class="w-full lg:w-6/12 lg:ltr:pl-24 lg:rtl:pr-24">
    <div class="relative mb-10" x-data="{ toggle: 'donut' }">
        <div class="flex border-b pb-2">
            <div class="w-full lg:w-11/12 text-xl text-left text-black-400">
                <h2 x-show="toggle === 'donut'">{{ trans('general.timeline') }}</h2>
                <h2 x-show="toggle === 'bar'">{{ trans('general.distribution') }}</h2>
            </div>

            <div class="w-full lg:w-1/12 flex items-center justify-center text-right cursor-pointer hover:shadow-lg hover:translate-y-0 hover:rounded-md" x-on:click="toggle === 'bar' ? toggle = 'donut' : toggle = 'bar'">
                <span class="material-icons-outlined" x-bind:class="toggle === 'donut' ? 'block': 'hidden'" title="{{ trans('general.distribution') }}">donut_small</span>
                <span class="material-icons" x-bind:class="toggle === 'bar' ? 'block': 'hidden'" title="{{ trans('general.timeline') }}">signal_cellular_alt</span>
            </div>
        </div>

        <div x-show="toggle === 'donut'">
            {!! $charts['bar']->container() !!}
        </div>

        <div class="apexcharts-donut-custom apexcharts-donut-custom-report" x-show="toggle === 'bar'">
            {!! $charts['donut']->container() !!}
        </div>
    </div>
</div>

@push('body_scripts')
    {!! $charts['bar']->script() !!}
    {!! $charts['donut']->script() !!}
@endpush
