<x-layouts.admin>
    <x-slot name="metaTitle">
        {{ $dashboard->name }}
    </x-slot>

    <x-slot name="title">
        {{ $dashboard->name }}
    </x-slot>

    <x-slot name="buttons">
        <!--Dashboard General Filter-->
        <el-date-picker
            v-model="filter_date"
            type="daterange"
            align="right"
            unlink-panels
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            @change="onChangeFilterDate"
            range-separator="-"
            start-placeholder="{{ $date_picker_shortcuts[trans('general.date_range.this_year')]['start'] }}"
            end-placeholder="{{ $date_picker_shortcuts[trans('general.date_range.this_year')]['end'] }}"
            popper-class="dashboard-picker"
            :picker-options="{
                shortcuts: [
                    @foreach ($date_picker_shortcuts as $text => $shortcut)
                        {
                            text: `{!! $text !!}`,
                            onClick(picker) {
                                const start = new Date('{{ $shortcut["start"] }}');
                                const end = new Date('{{ $shortcut["end"] }}');

                                picker.$emit('pick', [start, end]);
                            }
                        },
                    @endforeach
                ]
            }">
        </el-date-picker>
    </x-slot>

    @section('dashboard_action')
        @canany(['delete-common-dashboards', 'update-common-dashboards'])
            <div class="dashboard-action">
                <x-dropdown id="show-more-actions-dashboard">
                    <x-slot name="trigger" class="flex" override="class">
                        <span class="w-8 h-8 flex items-center justify-center px-2 py-2 ltr:ml-2 rtl:mr-2 hover:bg-gray-100 rounded-xl text-purple text-sm font-medium leading-6">
                            <span class="material-icons pointer-events-none">more_vert</span>
                        </span>
                    </x-slot>

                    @can('update-common-dashboards')
                        <x-dropdown.link href="{{ route('dashboards.edit', $dashboard->id) }}" id="show-more-actions-edit-dashboard-{{ $dashboard->id }}">
                            {{ trans('general.edit') }}
                        </x-dropdown.link>
                    @endcan

                    @can('delete-common-dashboards')
                        <x-delete-link :model="$dashboard" :route="'dashboards.destroy'" />

                        <div class="py-2 px-2">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                    @endcan

                    @can('update-common-dashboards')
                        <x-dropdown.link href="{{ route('dashboards.index') }}" id="show-more-actions-manage-dashboards">
                            {{ trans('general.title.manage', ['type' => trans_choice('general.dashboards', 2)]) }}
                        </x-dropdown.link>
                    @endcan
                </x-dropdown>
            </div>
        @endcanany

        @php
            $text = json_encode([
                'name' => trans('general.name'),
                'type' => trans_choice('general.types', 1),
                'width' => trans('general.width'),
                'sort' => trans('general.sort'),
                'enabled' => trans('general.enabled'),
                'yes' => trans('general.yes'),
                'no' => trans('general.no'),
                'save' => trans('general.save'),
                'cancel' => trans('general.cancel')
            ]);

            $placeholder = json_encode([
                'name' => trans('general.form.enter', ['field' => trans('general.name')]),
                'type' => trans('general.form.select.field', ['field' => trans_choice('general.types', 1)]),
                'width' => trans('general.form.select.field', ['field' => trans('general.width')]),
                'sort' => trans('general.form.enter', ['field' => trans('general.sort')])
            ]);
        @endphp

        <akaunting-widget
            v-if="widget_modal"
            :title="'{{ trans('general.title.edit') }}'.replace(':type', widget.name)"
            :show="widget_modal"
            :widget_id="widget.id"
            :name="widget.name"
            :width="widget.width"
            :action="widget.action"
            :type="widget.class"
            :types="widgets"
            :sort="widget.sort"
            :dashboard_id="{{ $dashboard->id }}"
            :text="{{ $text }}"
            :placeholder="{{ $placeholder }}"
            @cancel="onCancel">
        </akaunting-widget>
    @endsection

    <x-slot name="content">
        <div class="flex flex-col lg:flex-row justify-between items-start border-b pt-8">
            <div class="flex">
                @if ($user_dashboards->count() > 1)
                    <button type="button" id="dashboard-left" disabled="disabled" class="material-icons-outlined text-purple-200 bg-body -mr-3 mr-1">chevron_left</button>

                    <div id="dashboard-slider" class="flex space-x-10 overflow-scroll hide-scroll-bar">
                        @foreach ($user_dashboards as $user_dashboard)
                            <li 
                                id="show-dashboard-switch-{{ $user_dashboard->id }}"
                                class="relative flex-auto px-2 text-sm text-center pb-2 pt-1 cursor-pointer transition-all whitespace-nowrap list-none tabs-link"
                                x-bind:class="active != 'show-dashboard-switch-{{ $user_dashboard->id }}' ? 'text-black' : 'active-tabs text-purple border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md'"
                                @if ($loop->first)
                                style="margin-left:0px !important;"
                                @else
                                style="margin-left:10px !important;"
                                @endif
                            >
                                <a href="{{ route('dashboards.switch', $user_dashboard->id) }}">
                                    {{ $user_dashboard->name }}
                                </a>
                            </li>
                        @endforeach
                    </div>

                    <button type="button" id="dashboard-right" class="material-icons-outlined text-purple bg-body ml-1 mr-1">chevron_right</button>
                @endif
            </div>

            <div class="flex col-span-3 ml-6 text-right">
                @can('create-common-widgets')
                    <x-button
                        type="button"
                        id="show-more-actions-add-widget"
                        class="relative flex-auto px-3 pb-1.5 h-8 text-purple text-sm font-medium tabs-link"
                        override="class"
                        title="{{ trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]) }}"
                        @click="onCreateWidget()"
                    >
                        {{ trans('general.title.add', ['type' => trans_choice('general.widgets', 1)]) }}
                    </x-button>
                @endcan

                @can('create-common-dashboards')
                    <x-link href="{{ route('dashboards.create') }}" override="class" class="relative flex-auto px-3 pb-2.5 pt-1 h-8 text-purple text-sm font-medium tabs-link" id="show-more-actions-new-dashboard">
                        {{ trans('general.title.new', ['type' => trans_choice('general.dashboards', 1)]) }}
                    </x-link>
                @endcan
            </div>
        </div>

        <div class="dashboard flex flex-wrap px-6 lg:-mx-12">
            @foreach($widgets as $widget)
                @widget($widget)
            @endforeach
        </div>
    </x-slot>

    <x-script folder="common" file="dashboards" />
</x-layouts.admin>
