<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.dashboards', 2) }}</x-slot>

    <x-slot name="buttons">
        @can('create-common-dashboards')
            <x-link href="{{ route('dashboards.create') }}" kind="primary" id="index-more-actions-new-dasboard">
                {{ trans('general.title.new', ['type' => trans_choice('general.dashboards', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="App\Models\Common\Dashboard"
                bulk-action="App\BulkActions\Common\Dashboards"
            />

            <x-table>
                <x-table.thead>
                    <x-table.tr>
                        <x-table.th kind="bulkaction">
                            <x-index.bulkaction.all />
                        </x-table.th>

                        <x-table.th class="w-8/12 sm:w-5/12">
                            <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                        </x-table.th>

                        <x-table.th class="w-7/12" hidden-mobile kind="right">
                            {{ trans_choice('general.users', 1) }}
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @foreach($dashboards as $item)
                        <x-table.tr href="{{ route('dashboards.edit', $item->id) }}">
                            <x-table.td kind="bulkaction">
                                <x-index.bulkaction.single id="{{ $item->id }}" name="{{ $item->name }}" />
                            </x-table.td>

                            <x-table.td class="w-5/12">
                                {{ $item->name }}

                                @if (! $item->enabled)
                                    <x-index.disable text="{{ trans_choice('general.dashboards', 1) }}" />
                                @endif
                            </x-table.td>

                            <x-table.td class="w-7/12" hidden-mobile kind="right">
                                @if ($item->users)
                                    @foreach($item->users as $user)
                                        <span class="bg-lilac-900 px-3 py-1 text-sm rounded-lg text-black ltr:ml-3 rtl:mr-3">
                                            {{ !empty($user->name) ? $user->name : trans('general.na') }}
                                        </span>
                                    @endforeach
                                @endif
                            </x-table.td>

                            <x-table.td kind="action">
                                <x-table.actions :model="$item" />
                            </x-table.td>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>

            <x-pagination :items="$dashboards" />
        </x-index.container>
    </x-slot>

    <x-script folder="common" file="dashboards" />
</x-layouts.admin>
