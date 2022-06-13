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
            <x-link href="{{ route('categories.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('general.categories', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @can('create-settings-categories')
                <x-dropdown.link href="{{ route('import.create', ['settings', 'categories']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('categories.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="App\Models\Setting\Category"
                bulk-action="App\BulkActions\Settings\Categories"
            />

            <x-table>
                <x-table.thead>
                    <x-table.tr class="flex items-center px-1">
                        <x-table.th class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                            <x-index.bulkaction.all />
                        </x-table.th>

                        <x-table.th class="w-5/12">
                            <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                        </x-table.th>

                        <x-table.th class="w-5/12">
                            <x-sortablelink column="type" title="{{ trans_choice('general.types', 1) }}" />
                        </x-table.th>

                        <x-table.th class="w-2/12">
                            {{ trans('general.color') }}
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($categories as $category)
                        <x-table.tr href="{{ route('categories.edit', $category->id) }}" class="relative flex items-center border-b hover:bg-gray-100 px-1 group transition-all">
                            <x-table.td class="ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                <x-index.bulkaction.single id="{{ $category->id }}" name="{{ $category->name }}" />
                            </x-table.td>

                            <x-table.td class="w-5/12 truncate">
                                @if ($category->sub_categories->count())
                                    <div class="flex items-center font-bold">
                                        {{ $category->name }}
                                        <x-tooltip id="tooltip-category-{{ $category->id }}" placement="bottom" message="{{ trans('categories.collapse') }}">
                                            <button
                                                type="button"
                                                class="w-4 h-4 flex items-center justify-center mx-2 leading-none align-text-top rounded-lg bg-gray-500 hover:bg-gray-700"
                                                node="child-{{ $category->id }}"
                                                onClick="toggleSub('child-{{ $category->id }}', event)"
                                            >
                                                <span class="material-icons transform rotate-90 transition-all text-lg leading-none align-middle text-white">chevron_right</span>
                                            </button>
                                        </x-tooltip>
                                    </div>
                                @else
                                    <span class="font-bold">{{ $category->name }}</span>
                                @endif
                            </x-table.td>

                            <x-table.td class="w-5/12 truncate">
                                @if (! empty($types[$category->type]))
                                    {{ $types[$category->type] }}
                                @else
                                    <x-empty-data />
                                @endif
                            </x-table.td>

                            <x-table.td class="w-2/12 relative">
                                <span class="material-icons text-{{ $category->color }}" class="text-3xl" style="color:{{ $category->color }};">circle</span>
                            </x-table.td>

                            <x-table.td kind="action">
                                <x-table.actions :model="$category" />
                            </x-table.td>
                        </x-table.tr>

                        @foreach($category->sub_categories as $sub_category)
                            @include('settings.categories.sub_category', ['parent_category' => $category, 'sub_category' => $sub_category, 'tree_level' => 1])
                        @endforeach
                    @endforeach
                </x-table.tbody>
            </x-table>

            <x-pagination :items="$categories" />
        </x-index.container>
    </x-slot>

    <x-script folder="settings" file="categories" />
</x-layouts.admin>
