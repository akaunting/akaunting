@if ($sub_category->sub_categories)
    <x-table.tr data-collapse="child-{{ $parent_category->id }}" data-animation class="relative flex items-center hover:bg-gray-100 px-1 group border-b transition-all collapse-sub" href="{{ route('categories.edit', $sub_category->id) }}">
        <x-table.td kind="bulkaction">
            <x-index.bulkaction.single id="{{ $sub_category->id }}" name="{{ $sub_category->name }}" />
        </x-table.td>

        <x-table.td class="w-5/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-medium text-black truncate" style="padding-left: {{ $tree_level * 15 }}px;">
            @if ($sub_category->sub_categories->count())
                <div class="flex items-center font-bold">
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>

                    {{ $sub_category->name }}

                    <x-tooltip id="tooltip-category-{{ $parent_category->id }}" placement="bottom" message="{{ trans('categories.collapse') }}">
                        <button
                            type="button"
                            class="w-4 h-4 flex items-center justify-center mx-2 leading-none align-text-top rounded-lg bg-gray-500 hover:bg-gray-700"
                            node="child-{{ $sub_category->id }}"
                            onClick="toggleSub('child-{{ $sub_category->id }}', event)"
                        >
                            <span class="material-icons transform rotate-90 transition-all text-lg leading-none align-middle text-white">chevron_right</span>
                        </button>
                    </x-tooltip>
                </div>
            @else
                <div class="flex items-center font-bold">
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>
                    {{ $sub_category->name }}
                </div>
            @endif

            @if (! $sub_category->enabled)
                <x-index.disable text="{{ trans_choice('general.categories', 1) }}" />
            @endif
        </x-table.td>

        <x-table.td class="w-5/12 ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-normal text-black cursor-pointer truncate">
            @if (! empty($types[$item->type]))
                {{ $types[$item->type] }}
            @else
                <x-empty-data />
            @endif
        </x-table.td>

        <x-table.td class="ltr:pr-6 rtl:pl-6 py-4 ltr:text-left rtl:text-right whitespace-nowrap text-sm font-normal text-black cursor-pointer w-2/12 relative">
            <span class="material-icons text-3xl text-{{ $sub_category->color }}" style="color:{{ $sub_category->color }};">circle</span>
        </x-table.td>

        <x-table.td kind="action">
            <x-table.actions :model="$sub_category" />
        </x-table.td>
    </x-table.tr>

    @php
        $parent_category = $sub_category;
        $tree_level++;
    @endphp

    @foreach($sub_category->sub_categories as $sub_category)
        @include('settings.categories.sub_category', ['parent_category' => $parent_category, 'sub_category' => $sub_category, 'tree_level' => $tree_level])
    @endforeach
@endif
