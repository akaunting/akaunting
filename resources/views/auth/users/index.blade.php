<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('general.users', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('general.users', 2) }}"
        icon="people"
        route="users.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-auth-users')
            <x-link href="{{ route('users.create') }}" kind="primary" id="index-more-actions-invite-user">
                {{ trans('general.title.invite', ['type' => trans_choice('general.users', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="content">
        @if ($users->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="App\Models\Auth\User"
                    bulk-action="App\BulkActions\Auth\Users"
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

                            <x-table.th class="w-4/12" hidden-mobile>
                                <x-sortablelink column="email" title="{{ trans('general.email') }}" />
                            </x-table.th>

                            <x-table.th class="w-4/12" kind="right">
                                {{ trans_choice('general.roles', 1) }}
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($users as $item)
                            <x-table.tr href="{{ route('users.show', $item->id) }}">
                                <x-table.td kind="bulkaction">
                                    <x-index.bulkaction.single
                                        id="{{ $item->id }}"
                                        name="{{ $item->name }}"
                                        :disabled="($item->hasPendingInvitation() || user()->id == $item->id) ? true : false"
                                    />
                                </x-table.td>

                                <x-table.td class="w-8/12 sm:w-5/12">
                                    <div class="flex items-center space-x-2">
                                        @if (setting('default.use_gravatar', '0') == '1')
                                            <img src="{{ $item->picture }}" class="w-6 h-6 rounded-full mr-2 hidden lg:block text-transparent" title="{{ $item->name }}" alt="{{ $item->name }}">
                                        @elseif (is_object($item->picture))
                                            <img src="{{ Storage::url($item->picture->id) }}" class="w-6 h-6 rounded-full mr-2 hidden lg:block text-transparent" alt="{{ $item->name }}" title="{{ $item->name }}">
                                        @else
                                            <img src="{{ asset('public/img/user.svg') }}" class="w-6 h-6 rounded-full mr-2 hidden lg:block text-transparent" alt="{{ $item->name }}"/>
                                        @endif

                                        {{ !empty($item->name) ? $item->name : trans('general.na') }}

                                        @if ($item->hasPendingInvitation())
                                            <x-index.status status="pending" background-color="bg-status-danger" text-color="text-black" />
                                        @endif

                                        @if (! $item->enabled)
                                            <x-index.disable text="{{ trans_choice('general.users', 1) }}" />
                                        @endif
                                    </div>
                                </x-table.td>

                                <x-table.td class="w-4/12" hidden-mobile>
                                    {{ $item->email }}
                                </x-table.td>

                                <x-table.td class="w-4/12" kind="right">
                                    <div class="space-x-2">
                                        @foreach($item->roles as $role)
                                            <span class="bg-lilac-900 px-2 py-0.5 test-xs rounded-lg text-black">
                                                {{ $role->display_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </x-table.td>

                                <x-table.td kind="action">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$users" />
            </x-index.container>
        @else
            <x-empty-page group="auth" page="users" />
        @endif
    </x-slot>

    <x-script folder="auth" file="users" />
</x-layouts.admin>
