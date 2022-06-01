<x-layouts.admin>
    <x-slot name="title">
        {{ trans_choice('general.updates', 2) }}
    </x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('updates.check') }}">
            {{ trans('updates.check') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <div class="my-10">
            <div class="flex items-center">
                <div class="relative px-4 text-sm text-center pb-2 text-purple font-medium border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md">
                    <span>Akaunting</span>
                </div>
            </div>

            <x-table>
                <x-table.tbody>
                    <x-table.tr>
                        @if (empty($core))
                            <x-table.td class="w-12/12" kind="cursor-none">
                                {{ trans('updates.latest_core') }}
                            </x-table.td>
                        @else
                            <x-table.td class="w-6/12" kind="cursor-none">
                                {{ trans('updates.new_core') }}
                            </x-table.td>

                            <x-table.td kind="right" class="w-6/12" kind="cursor-none">
                                <x-slot name="first" class="text-right" override="class">
                                    <x-link href="{{ route('updates.run', ['alias' => 'core', 'version' => $core]) }}" class="px-3 py-1.5 rounded-xl text-sm font-medium leading-6 ltr:mr-2 rtl:ml-2 bg-green text-white hover:bg-green-700 disabled:bg-green-100" override="class">
                                        {{ trans('updates.update', ['version' => $core]) }}
                                    </x-link>

                                    <x-button @click="onChangelog">
                                        {{ trans('updates.changelog') }}
                                    </x-button>
                                </x-slot>
                            </x-table.td>
                        @endif
                    </x-table.tr>
                </x-table.tbody>
            </x-table>
        </div>

        <div class="flex items-center">
            <div class="relative px-4 text-sm text-center pb-2 text-purple font-medium border-purple transition-all after:absolute after:w-full after:h-0.5 after:left-0 after:right-0 after:bottom-0 after:bg-purple after:rounded-tl-md after:rounded-tr-md">
                {{ trans_choice('general.modules', 2) }}
            </div>
        </div>

        <x-index.container class="my-0" override="class">
            <x-table>
                <x-table.thead>
                    <x-table.tr class="flex items-center px-1">
                        <x-table.th class="w-3/12">
                            {{ trans('general.name') }}
                        </x-table.th>

                        <x-table.th class="w-3/12 hidden sm:table-cell">
                            {{ trans('updates.installed_version') }}
                        </x-table.th>

                        <x-table.th class="w-3/12 hidden sm:table-cell">
                            {{ trans('updates.latest_version') }}
                        </x-table.th>

                        <x-table.th class="w-3/12" kind="right">
                            {{ trans('general.actions') }}
                        </x-table.th>
                    </x-table.tr>
                </x-table.thead>

                <x-table.tbody>
                    @if ($modules)
                        @foreach($modules as $module)
                        <x-table.tr>
                            <x-table.td class="w-3/12" kind="cursor-none">
                                {{ $module->name }}
                            </x-table.td>

                            <x-table.td class="w-3/12" kind="cursor-none">
                                {{ $module->installed }}
                            </x-table.td>

                            <x-table.td class="w-3/12" kind="cursor-none">
                                {{ $module->latest }}
                            </x-table.td>

                            <x-table.td class="w-3/12" kind="right">
                                <x-link href="{{ route('updates.run', ['alias' => $module->alias, 'version' => $module->latest]) }}" kind="primary">
                                    {{ trans_choice('general.updates', 1) }}
                                </x-link>
                            </x-table.td>
                        </x-table.tr>
                        @endforeach
                    @else
                        <x-table.tr>
                            <x-table.td class="w-4/12">
                                <small>{{ trans('general.no_records') }}</small>
                            </x-table.td>
                        </x-table.tr>
                    @endif
                </x-table.tbody>
            </x-table>
        </x-index.container>

        <akaunting-modal v-if="changelog.show"
            modal-dialog-class="max-w-screen-2xl"
            :show="changelog.show"
            :title="'{{ trans('updates.changelog') }}'"
            @cancel="changelog.show = false"
            :message="changelog.html">
            <template #card-footer>
                <span></span>
            </template>
        </akaunting-modal>
    </x-slot>

    <x-script folder="install" file="update" />
</x-layouts.admin>
