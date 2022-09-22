<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.reports', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.reports', 2) }}"
        icon="donut_small"
        route="reports.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-common-reports')
            <x-link href="{{ route('reports.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.reports', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        <livewire:report.pins :categories="$categories" />

        @foreach ($categories as $category)
            @php $category_id = $loop->index; @endphp
            <div
                @class([
                    'mb-14',
                    'mt-4' => (! $loop->first) ? false : true,
                    'mt-12' => ($loop->first) ? false : true,
                ])
            >
                <div class="px-2">
                    <x-form.section.head title="{!! $category['name'] !!}" description="{{ $category['description'] }}" />
                </div>

                <div class="grid sm:grid-cols-6 gap-12 my-3.5">
                    @foreach($category['reports'] as $report)
                        <div class="flex justify-between sm:col-span-3 p-1 group">
                            <div class="lg:w-80">
                                <x-link href="{{ route('reports.show', $report->id) }}" class="flex" override="class">
                                    <span class="material-icons-outlined text-5xl transform transition-all hover:scale-125 text-black-400">
                                        {{ $icons[$report->id] }}
                                    </span>

                                    <div class="ltr:ml-2 rtl:mr-2">
                                        <h2 class="mb-1">
                                            <x-link.hover group-hover>
                                                {!! $report->name !!}
                                            </x-link.hover>
                                        </h2>

                                        <span class="text-black-400 text-sm">
                                            {!! $report->description !!}
                                        </span>
                                    </div>
                                </x-link>
                            </div>

                            <div class="flex items-start ltr:space-x-2 rtl:space-x-reverse">
                                <livewire:report.pin :categories="$categories" :report="$report" />

                                @canany(['create-common-reports', 'update-common-reports', 'delete-common-reports'])
                                <x-dropdown id="index-line-actions-report-{{ $category_id }}-{{ $report->id }}">
                                    <x-slot name="trigger" class="flex" override="class">
                                        <span class="w-8 h-8 flex items-center justify-center px-2 py-2 rtl:mr-4 hover:bg-gray-100 rounded-xl text-purple text-sm font-medium leading-6">
                                            <span class="material-icons pointer-events-none">more_vert</span>
                                        </span>
                                    </x-slot>

                                    @can('update-common-reports')
                                        <x-dropdown.link href="{{ route('reports.edit', $report->id) }}" id="index-line-actions-edit-report-{{ $report->id }}">
                                            {{ trans('general.edit') }}
                                        </x-dropdown.link>
                                    @endcan

                                    @can('create-common-reports')
                                        <x-dropdown.link href="{{ route('reports.duplicate', $report->id) }}" id="index-line-actions-duplicate-report-{{ $report->id }}">
                                            {{ trans('general.duplicate') }}
                                        </x-dropdown.link>
                                    @endcan

                                    @can('delete-common-reports')
                                        <x-delete-link :model="$report" route="reports.destroy" />
                                    @endcan
                                </x-dropdown>
                                @endcanany
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
