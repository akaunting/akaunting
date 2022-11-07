<div id="widget-{{ $class->model->id }}" class="w-full my-8 px-12">
    @include($class->views['header'], ['header_class' => ''])

    <div class="flex flex-col-reverse lg:flex-row mt-3">
        <div class="w-full lg:w-11/12 apex-chart-cash-flow">
            {!! $chart->container() !!}
        </div>

        <div class="w-full lg:w-1/12 flex flex-row lg:flex-col items-center justify-around sm:justify-start lg:mt-11 space-y-0 sm:space-y-2">
            <div class="relative w-32 lg:w-auto flex flex-col items-center sm:justify-between text-center">
                <div class="flex justify-end lg:block text-lg">
                    <x-tooltip id="tooltip-cashflow-incoming" placement="top" message="{{ $totals['incoming_exact'] }}">
                        {{ $totals['incoming_for_humans'] }}
                    </x-tooltip>
                </div>

                <span class="text-green text-xs hidden lg:block">
                    {{ trans('general.incoming') }}
                </span>

                <span class="absolute lg:relative hidden lg:block material-icons mt-2 -right-12 lg:right-0">remove</span>
            </div>

            <div class="relative w-32 lg:w-auto flex flex-col items-center sm:justify-between">
                <div class="flex justify-end lg:block text-lg">
                    <x-tooltip id="tooltip-cashflow-outgoing" placement="top" message="{{ $totals['outgoing_exact'] }}">
                        {{ $totals['outgoing_for_humans'] }}
                    </x-tooltip>
                </div>

                <span class="text-rose text-xs hidden lg:block">
                    {{ trans('general.outgoing') }}
                </span>

                <span class="absolute lg:relative hidden lg:block material-icons mt-2 -right-12 lg:right-0">drag_handle</span>
            </div>

            <div class="relative w-32 lg:w-auto flex flex-col items-center sm:justify-between">
                <div class="flex justify-end lg:block text-lg">
                    <x-tooltip id="tooltip-cashflow-profit" placement="top" message="{{ $totals['profit_exact'] }}">
                        {{ $totals['profit_for_humans'] }}
                    </x-tooltip>
                </div>

                <span class="text-purple text-xs hidden lg:block">{{ trans_choice('general.profits', 1) }}</span>
            </div>
        </div>
    </div>
</div>

@push('body_scripts')
    {!! $chart->script() !!}
@endpush
