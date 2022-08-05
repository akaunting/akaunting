@php $charts = $class->getCharts($table_key); @endphp

<div class="w-full lg:w-6/12 lg:ltr:pl-24 lg:rtl:pr-24">
    <div class="relative mb-10" x-data="{ toggle: 'donut' }">
        <div class="flex border-b pb-2">
            <div class="w-full lg:w-11/12 ltr:text-xl rtl:text-right text-left text-black-400">
                <h2 x-show="toggle === 'donut'">{{ trans('general.timeline') }}</h2>
                <h2 x-show="toggle === 'bar'">{{ trans('general.distribution') }}</h2>
            </div>

            <div class="flex items-center bg-gray-200 p-1 rounded-lg">
                <button type="button"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="toggle === 'donut' ? 'bg-white rounded-lg' : 'btn-outline-primary'"
                    x-on:click="toggle = 'donut'"
                >
                    <span class="material-icons" title="{{ trans('general.timeline') }}">signal_cellular_alt</span>
                </button>

                <button type="button"
                    class="w-18 flex justify-center px-2"
                    x-bind:class="toggle === 'bar' ? 'bg-white rounded-lg' : 'btn-outline-primary'"
                    x-on:click="toggle = 'bar'"
                >
                    <span class="material-icons-outlined" title="{{ trans('general.distribution') }}">donut_small</span>
                </button>
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
