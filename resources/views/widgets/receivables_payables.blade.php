<div id="widget-{{ $class->model->id }}" class="{{ $class->model->settings->width }} my-8">
    @include($class->views['header'], ['header_class' => 'border-b-0'])

    <div class="my-3 text-black-400 text-sm">
        {{ $grand_total_text }}: <span class="font-bold">{{ $totals['grand'] }}</span>
    </div>

    <div class="my-3" aria-hidden="true">
        <div @class(['h-3', 'rounded-md', 'bg-red-300' => $has_progress, 'bg-gray-300' => ! $has_progress])>
            <div @class(['h-3', 'rounded-md', 'bg-orange-300' => $has_progress, 'bg-gray-300' => ! $has_progress]) style="width: {{ $progress }}%;"></div>
        </div>
    </div>

    <div class="flex">
        <div class="ltr:mr-4 rtl:ml-4">
            <span @class(['flex', 'text-sm', 'text-orange' => $has_progress, 'text-black' => ! $has_progress])>
                {{ trans('general.open') }}
            </span>

            <div class="flex items-center text-black-400 font-medium">
                {{ $totals['open'] }}
            </div>
        </div>

        <div class="ml-4">
            <span @class(['flex', 'text-sm', 'text-red' => $has_progress, 'text-black' => ! $has_progress])>
                {{ trans('general.overdue') }}
            </span>

            <button id="dashboard-widget-{{ strtolower(class_basename($class)) }}-overdue" type="button" class="flex items-center text-black-400 font-medium group" data-dropdown-toggle="widgets-list-{{ $class->model->id }}">
                <x-button.hover color="to-black-400" group-hover>
                    {{ $totals['overdue'] }}
                </x-button.hover>

                <div class="relative flex">
                    <span class="material-icons-round cursor-pointer">arrow_drop_down</span>

                    <div id="widgets-list-{{ $class->model->id }}" class="absolute right-0 mt-3 py-2 bg-white rounded-md border border-gray-200 shadow-xl z-20 hidden" style="left: auto; min-width: 10rem;">
                        @foreach ($periods as $name => $amount)
                            <div id="dashboard-widget-{{ strtolower(class_basename($class)) }}-{{ str_replace('_', '-', $name) }}" class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap cursor-auto">
                                <div class="w-full h-full flex items-center justify-between rounded-md px-2 text-sm hover:bg-lilac-100">
                                    <div class="font-normal text-sm">
                                        {{ trans('widgets.periods.' . $name) }}
                                    </div>

                                    <div class="ltr:pl-10 rtl:pr-10 text-sm">
                                        {{ $amount }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </button>
        </div>
    </div>
</div>
