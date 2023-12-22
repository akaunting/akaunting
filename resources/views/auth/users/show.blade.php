<x-layouts.admin>
    <x-slot name="title">
        {{ $user->name }}
    </x-slot>

    <x-slot name="info">
        @if (! $user->enabled)
            <x-index.disable text="{{ trans_choice('general.users', 1) }}" />
        @endif
    </x-slot>

    <x-slot name="favorite"
        title="{{ $user->name }}"
        icon="person"
        :route="['users.show', $user->id]"
    ></x-slot>

    <x-slot name="buttons">
        @stack('create_button_start')

        @stack('edit_button_start')

        @can('update-auth-users')
        <x-link href="{{ route('users.edit', $user->id) }}" id="show-more-actions-edit-user">
            {{ trans('general.edit') }}
        </x-link>
        @endcan

        @stack('edit_button_end')
    </x-slot>

    <x-slot name="moreButtons">
        @stack('more_button_start')

        <x-dropdown id="show-more-actions-user">
            <x-slot name="trigger">
                <span class="material-icons pointer-events-none">more_horiz</span>
            </x-slot>

            @stack('delete_button_start')

            @can('delete-auth-users')
            <x-delete-link :model="$user" route="users.destroy" />
            @endcan

            @stack('delete_button_end')
        </x-dropdown>

        @stack('more_button_end')
    </x-slot>

    <x-slot name="content">
        <x-show.container>
            <x-show.summary>
                @stack('profile_start')

                <x-show.summary.left>
                    <x-slot name="avatar">
                        @if (setting('default.use_gravatar', '0') == '1')
                            <img src="{{ $user->picture }}" class="absolute w-12 h-12 rounded-full hidden lg:block" title="{{ $user->name }}" alt="{{ $user->name }}">
                        @elseif (is_object($user->picture))
                            <img src="{{ Storage::url($user->picture->id) }}" class="absolute w-12 h-12 rounded-full hidden lg:block" alt="{{ $user->name }}" title="{{ $user->name }}">
                        @else
                            <img src="{{ asset('public/img/user.svg') }}" class="absolute w-12 h-12 rounded-full hidden lg:block" alt="{{ $user->name }}"/>
                        @endif
                    </x-slot>

                    <span>{{ $user->email }}</span>
                </x-show.summary.left>

                <x-show.summary.right>
                </x-show.summary.right>
            </x-show.summary>

            <x-show.content>
                <x-show.content.left>
                    @stack('name_input_start')
                    @stack('name_input_end')

                    @stack('logo_input_start')
                    @stack('logo_input_end')

                    @stack('email_input_start')
                    @stack('email_input_end')

                    @stack('roles_input_start')
                    <div class="flex flex-col text-sm sm:mb-5">
                        <div class="font-medium">{{ trans_choice('general.roles', 1) }}</div>
                        <span>{{ $user->roles()?->first()?->display_name }}</span>
                    </div>
                    @stack('roles_input_end')

                    @stack('landing_page_input_start')
                    <div class="flex flex-col text-sm sm:mb-5">
                        <div class="font-medium">{{ trans('auth.landing_page') }}</div>
                        <span>{{ isset($landing_pages[$user->landing_page]) ?  $landing_pages[$user->landing_page] : trans('general.na') }}</span>
                    </div>
                    @stack('landing_page_input_end')

                    @stack('locale_input_start')
                    <div class="flex flex-col text-sm sm:mb-5">
                        <div class="font-medium">{{ trans_choice('general.languages', 1)  }}</div>
                        <span>{{ language()->allowed()[$user->locale] }}</span>
                    </div>
                    @stack('locale_input_end')
                </x-show.content.left>

                <x-show.content.right>
                    <x-tabs active="companies">
                        <x-slot name="navs">
                            @stack('companies_nav_start')

                            <x-tabs.nav
                                id="companies"
                                name="{{ trans_choice('general.companies', 2) }}"
                                active
                            />

                            @stack('companies_nav_end')
                        </x-slot>

                        <x-slot name="content">
                            @stack('companies_tab_start')

                            <x-tabs.tab id="companies">
                                <x-table>
                                    <x-table.thead>
                                        <x-table.tr>
                                            <x-table.th class="w-2/12 sm:w-1/12">
                                                <x-sortablelink column="id" title="{{ trans('general.id') }}" />
                                            </x-table.th>

                                            <x-table.th class="w-8/12 sm:w-4/12">
                                                <x-slot name="first" class="flex items-center">
                                                    <x-sortablelink column="name" title="{{ trans('general.name') }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    <x-sortablelink column="tax_number" title="{{ trans('general.tax_number') }}" />
                                                </x-slot>
                                            </x-table.th>

                                            <x-table.th class="w-4/12" hidden-mobile>
                                                <x-slot name="first">
                                                    <x-sortablelink column="email" title="{{ trans('general.email') }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    <x-sortablelink column="phone" title="{{ trans('general.phone') }}" />
                                                </x-slot>
                                            </x-table.th>

                                            <x-table.th class="w-3/12" kind="right">
                                                <x-slot name="first">
                                                    <x-sortablelink column="country" title="{{ trans_choice('general.countries', 1) }}" />
                                                </x-slot>
                                                <x-slot name="second">
                                                    <x-sortablelink column="currency" title="{{ trans_choice('general.currencies', 1) }}" />
                                                </x-slot>
                                            </x-table.th>
                                        </x-table.tr>
                                    </x-table.thead>

                                    <x-table.tbody>
                                        @foreach($companies as $item)
                                            @if (in_array($item->id, user()->company_ids))
                                                <x-table.tr>
                                                    <x-table.td class="w-2/12 sm:w-1/12">
                                                        {{ $item->id }}
                                                    </x-table.td>

                                                    <x-table.td class="w-8/12 sm:w-4/12">
                                                        <x-slot name="first" class="flex" override="class">
                                                            <div class="font-bold truncate">
                                                                {{ $item->name }}
                                                            </div>

                                                            @if (! $item->enabled)
                                                                <x-index.disable text="{{ trans_choice('general.companies', 1) }}" />
                                                            @endif
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            @if ($item->tax_number)
                                                                {{ $item->tax_number }}
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12" hidden-mobile>
                                                        <x-slot name="first">
                                                            @if ($item->email)
                                                                {{ $item->email }}
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            @if ($item->phone)
                                                                {{ $item->phone }}
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" kind="amount">
                                                        <x-slot name="first">
                                                            @if ($item->country)
                                                                <x-index.country code="{{ $item->country }}" />
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            @if ($item->currency)
                                                                <x-index.currency code="{{ $item->currency }}" />
                                                            @else
                                                                <x-empty-data />
                                                            @endif
                                                        </x-slot>
                                                    </x-table.td>
                                                </x-table.tr>
                                            @else
                                                <x-table.tr>
                                                    <x-table.td class="w-2/12 sm:w-1/12">
                                                        ***
                                                    </x-table.td>

                                                    <x-table.td class="w-8/12 sm:w-4/12">
                                                        <x-slot name="first" class="flex" override="class">
                                                            <div class="font-bold truncate">
                                                                ***
                                                            </div>
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            ***
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-4/12" hidden-mobile>
                                                        <x-slot name="first">
                                                            ***
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            ***
                                                        </x-slot>
                                                    </x-table.td>

                                                    <x-table.td class="w-3/12" kind="amount">
                                                        <x-slot name="first">
                                                            ***
                                                        </x-slot>
                                                        <x-slot name="second">
                                                            ***
                                                        </x-slot>
                                                    </x-table.td>
                                                </x-table.tr>
                                            @endif
                                        @endforeach
                                    </x-table.tbody>
                                </x-table>
                            </x-tabs.tab>

                            @stack('companies_tab_end')
                        </x-slot>
                    </x-tabs>
                </x-show.content.right>
            </x-show.content>
        </x-show-container>
    </x-slot>

    <x-contacts.script type="user" />
</x-layouts.admin>
