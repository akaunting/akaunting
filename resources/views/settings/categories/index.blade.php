<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.categories', 2) }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.categories', 2) }}"
        icon="folder"
        route="categories.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-settings-categories')
            <x-link href="{{ route('categories.create') }}" kind="primary" id="index-more-actions-new-category">
                {{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @can('create-settings-categories')
                <x-dropdown.link href="{{ route('import.create', ['settings', 'categories']) }}" id="index-more-actions-import-category">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('categories.export', request()->input()) }}" id="index-more-actions-export-category">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-tabs active="{{ $tab_active }}">
                <x-slot name="navs">
                    @foreach($tabs as $tab => $data)
                        @if ($tab_active == $tab)
                            <x-tabs.nav-pin
                                id="{{ $tab }}"
                                name="{{ $data['name'] }}"
                                type="categories"
                                tab="{{ $tab }}"
                            />
                        @else
                            <x-tabs.nav-pin
                                id="{{ $tab }}"
                                href="{{ route('categories.index', ['search' => 'type:' . $data['key']]) }}"
                                name="{{ $data['name'] }}"
                                type="categories"
                                tab="{{ $tab }}"
                            />
                        @endif
                    @endforeach

                    @if ($tab_active == 'categories-all')
                        <x-tabs.nav-pin
                            id="categories-all"
                            name="{{ trans('general.all_type', ['type' => trans_choice('general.categories', 2)]) }}"
                            type="categories"
                            tab="all"
                        />
                    @else
                        <x-tabs.nav-pin
                            id="categories-all"
                            href="{{ route('categories.index', ['list_records' => 'all']) }}"
                            name="{{ trans('general.all_type', ['type' => trans_choice('general.categories', 2)]) }}"
                            type="categories"
                            tab="all"
                        />
                    @endif
                </x-slot>

                <x-slot name="content">
                    @php
                        $name_class = $hide_code_column ? 'w-5/12' : 'w-4/12';
                    @endphp

                    <x-tabs.tab id="{{ $tab_active }}">
                        <x-index.search
                            search-string="App\Models\Setting\Category"
                            bulk-action="App\BulkActions\Settings\Categories"
                        />

                        <x-table>
                            <x-table.thead>
                                <x-table.tr>
                                    <x-table.th kind="bulkaction">
                                        <x-index.bulkaction.all />
                                    </x-table.th>

                                    @if (!$hide_code_column)
                                        <x-table.th class="w-1/12">
                                            <x-sortablelink column="code" title="{{ trans('general.code') }}" />
                                        </x-table.th>
                                    @endif

                                    <x-table.th class="{{ $name_class }}">
                                        <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                                    </x-table.th>

                                    <x-table.th class="w-3/12">
                                        <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                                    </x-table.th>

                                    <x-table.th class="w-2/12 ltr:text-right rtl:text-left">
                                        {{ trans('general.balance') }}
                                    </x-table.th>
                                </x-table.tr>
                            </x-table.thead>

                            <x-table.tbody>
                                @foreach($categories as $item)
                                    <x-table.tr href="{{ route('categories.edit', $item->id) }}">
                                        <x-table.td kind="bulkaction">
                                            <x-index.bulkaction.single
                                                id="{{ $item->id }}"
                                                name="{{ $item->name }}"
                                                :disabled="($item->isTransferCategory()) ? true : false"
                                            />
                                        </x-table.td>

                                        @if (!$hide_code_column)
                                            <x-table.td class="w-1/12">
                                                @if(!empty($item->code))
                                                    {{ $item->code }}
                                                @else
                                                    <x-empty-data />
                                                @endif
                                            </x-table.td>
                                        @endif

                                        <x-table.td class="{{ $name_class }}">
                                            @if ($item->sub_categories->count())
                                                <div class="flex items-center">
                                                    <x-tooltip id="tooltip-category-{{ $item->id }}" placement="bottom" message="{{ trans('categories.collapse') }}">
                                                        <button
                                                            type="button"
                                                            class="w-4 h-4 flex items-center justify-center mx-2 leading-none align-text-top rounded-lg"
                                                            node="child-{{ $item->id }}"
                                                            onClick="toggleSub('child-{{ $item->id }}', event)"
                                                        >
                                                            <span class="material-icons transform rotate-90 -ml-2 transition-all text-xl leading-none align-middle rounded-full bg-{{ $item->color }} text-white" style="background-color:{{ $item->color }};">chevron_right</span>
                                                        </button>
                                                    </x-tooltip>

                                                    <div class="flex items-center font-bold">
                                                        {{ $item->name }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <span class="material-icons text-{{ $item->color }}" class="text-3xl" style="color:{{ $item->color }};">circle</span>

                                                    <span class="font-bold ltr:ml-2 rtl:mr-2">
                                                        {{ $item->name }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if (! $item->enabled)
                                                <x-index.disable text="{{ trans_choice('general.categories', 1) }}" />
                                            @endif
                                        </x-table.td>

                                        <x-table.td class="w-3/12">
                                            @if (! empty($types[$item->type]))
                                                {{ $types[$item->type] }}
                                            @else
                                                <x-empty-data />
                                            @endif
                                        </x-table.td>

                                        <x-table.td class="w-2/12 ltr:text-right rtl:text-left">
                                            <x-index.balance :amount="$item->balance" />
                                        </x-table.td>

                                        <x-table.td kind="action">
                                            <x-table.actions :model="$item" />
                                        </x-table.td>
                                    </x-table.tr>

                                    @foreach($item->sub_categories as $sub_category)
                                        @include('settings.categories.sub_category', ['parent_category' => $item, 'sub_category' => $sub_category, 'tree_level' => 1, 'hide_code_column' => $hide_code_column, 'name_class' => $name_class])
                                    @endforeach
                                @endforeach
                            </x-table.tbody>
                        </x-table>

                        <x-pagination :items="$categories" />
                    </x-tabs.tab>
                </x-slot>
            </x-tabs>
        </x-index.container>
    </x-slot>

    <x-script folder="settings" file="categories" />
</x-layouts.admin>
