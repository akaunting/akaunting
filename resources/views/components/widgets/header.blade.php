@canany(['update-common-widgets', 'delete-common-widgets'])
<div class="pb-2 my-4 lg:my-0{{ !empty($header_class) ? ' ' . $header_class : '' }}">
    <div class="flex justify-between font-medium mb-2">
        <h2 class="text-black" title="{{ $class->model->name }}">
            {{ $class->model->name }}
        </h2>

        <div class="flex items-center">
            @if ($report = $class->getReportUrl())
                @if ($class->model?->settings?->raw_width == '25' || $class->model?->settings?->width == 'w-full lg:w-1/4 lg:px-6')
                    <x-link href="{{ $report }}" class="lg:flex hidden text-purple hover:bg-gray-100 rounded-xl w-8 h-8 items-center justify-center text-sm text-right" override="class">
                        <x-tooltip id="tooltip-view-report" placement="top" message="{{ trans('widgets.view_report') }}" class="text-black left-5">
                            <x-icon icon="visibility" class="text-lg font-normal"></x-icon>
                        </x-tooltip>
                    </x-link>

                    <x-link href="{{ $report }}" class="lg:hidden text-purple text-sm text-right" override="class">
                        {{ trans('widgets.view_report') }}
                    </x-link>
                @else
                    <x-link href="{{ $report }}" class="text-purple text-sm mr-3 text-right" override="class">
                        <x-link.hover color="to-purple">
                            {{ trans('widgets.view_report') }}
                        </x-link.hover>
                    </x-link>
                @endif
            @endif

            <x-dropdown id="show-more-actions-widget-{{ $class->model->id }}">
                <x-slot name="trigger" class="flex" override="class">
                    <span class="w-8 h-8 flex items-center justify-center px-2 py-2 hover:bg-gray-100 rounded-xl text-purple text-sm font-medium leading-6">
                        <span class="material-icons pointer-events-none">more_vert</span>
                    </span>
                </x-slot>

                @can('update-common-widgets')
                <div class="w-full flex items-center text-purple px-2 h-9 leading-9 whitespace-nowrap">
                    <x-button
                        type="button"
                        id="show-more-actions-edit-widget-{{ $class->model->id }}"
                        class="w-full h-full flex items-center rounded-md px-2 text-sm hover:bg-lilac-100"
                        override="class"
                        title="{{ trans('general.edit') }}"
                        @click="onEditWidget('{{ $class->model->id }}')"
                    >
                        {{ trans('general.edit') }}
                    </x-button>
                </div>
                @endcan

                @can('delete-common-widgets')
                <div class="dropdown-divider"></div>

                <x-delete-link :model="$class->model" route="widgets.destroy" />
                @endcan
            </x-dropdown>
        </div>
    </div>

    <span class="h-6 block border-b text-black-400 text-xs truncate">
        {{ $class->getDescription() }}
    </span>
</div>
@endcanany
