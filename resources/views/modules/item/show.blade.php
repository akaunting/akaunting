<x-layouts.modules>
    <x-slot name="title">
        {{ trans_choice('general.modules', 2) }}
    </x-slot>

    <x-slot name="buttons">
        <x-link href="{{ route('apps.api-key.create') }}">
            {{ trans('modules.api_key') }}
        </x-link>

        <x-link href="{{ route('apps.my.index') }}">
            {{ trans('modules.my_apps') }}
        </x-link>
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col space-y-16 py-4">
            <div class="flex flex-col lg:flex-row w-full space-x-16 space-y-0">
                <div class="w-full lg:w-7/12 flex flex-col space-x-2 banner">
                    @foreach ($module->files as $file)
                        @if ($loop->first)
                            <div class="relative w-full">
                                <img src="{{ $file->path_string }}" class="w-full h-auto rounded-xl" />

                                @if ($module->video)
                                @php
                                    if (strpos($module->video->link, '=') !== false) {
                                        $code = explode('=', $module->video->link);
                                        $code[1]= str_replace('&list', '', $code[1]);
                                    }
                                @endphp

                                <a href="https://www.youtube-nocookie.com/embed/{{ $code[1] }}" class="glightbox-video absolute flex items-center justify-around top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-white bg-opacity-75 hover:bg-opacity-100 rounded-full">
                                    <i class="material-icons text-purple text-7xl">play_arrow</i>
                                </a>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    @if ($module->categories)
                        <div class="flex justify-between">
                            <span>
                                @foreach ($module->categories as $module_category)
                                    <a href="{{ route('apps.categories.show', $module_category->slug) }}" class="text-sm">
                                        {{ $module_category->name }}
                                    </a>
                                    @if (! $loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>

                <div class="w-full lg:w-5/12" x-data="{ price_type : true }">
                    <div class="flex flex-col space-y-6">
                        <div class="flex flex-col">
                            <div class="flex flex-col space-y-4">
                                @if ($module->vote)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex">
                                            @for ($i = 1; $i <= $module->vote; $i++)
                                                <i class="material-icons text-orange text-sm">star</i>
                                            @endfor

                                            @for ($i = $module->vote; $i < 5; $i++)
                                                <i class="material-icons text-sm">star_border</i>
                                            @endfor
                                        </div>

                                        <p class="text-xs">
                                            @if ($module->total_review)
                                            ( {{ $module->total_review }} {{ trans('modules.tab.reviews') }} )
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <div class="flex flex-col">
                                    <div class="flex flex-wrap items-baseline space-x-4">
                                        <h3 class="text-4xl font-semibold text-black">
                                            {!! $module->name !!}
                                        </h3>

                                        @if ($module->vendor_name)
                                            <span class="text-sm">
                                                by
                                                <a id="apps-vendor" class="border-b border-dashed border-black transition-all hover:font-semibold" href="{{ route('apps.vendors.show', $module->vendor->slug) }}">
                                                    {{ $module->vendor_name }}
                                                </a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (! in_array('onprime', $module->where_to_use))
                            @if (! empty($module->cloud_information))
                                {!! $module->cloud_information !!}
                            @else
                                <div class="text-center text-sm mt-3 mb--2 bg-red-100 rounded-lg p-2 cursor-default">
                                    <span class="text-sm text-red-700">
                                        {!! trans('modules.only_works_cloud') !!}
                                    </span>
                                </div>
                            @endif
                        @else
                            <div class="text-sm truncate line-clamp-1">
                                {!! $module->description !!}
                            </div>

                            <div class="relative flex flex-col lg:flex-row space-x-4 justify-between">
                                <x-layouts.modules.show.price :module="$module" />

                                <div class="flex w-1/2 lg:justify-center">
                                    @if ($module->price != '0.0000')
                                        <x-layouts.modules.show.toggle />
                                    @endif
                                </div>
                            </div>

                            <x-layouts.modules.show.information :module="$module" />
                        @endif
                    </div>

                    <div class="flex justify-around space-x-12 mt-5">
                        <x-layouts.modules.show.buttons :module="$module" :installed="$installed" :enable="$enable" />
                    </div>
                </div>
            </div>

            <div class="tabs w-full">
                <x-tabs class="flex border-b -mb-1 space-x-2 overflow-x-scroll lg:overflow-visible" active="{{ ! empty($module->call_to_actions) ? 'features' : 'description' }}">
                    <x-slot name="navs">
                        @stack('features_nav_start')

                        @if ($module->call_to_actions)
                            <x-tabs.nav
                                id="features"
                                name="{{ trans('modules.tab.features') }}"
                                active
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @else
                            <x-tabs.nav
                                id="description"
                                name="{{ trans('general.description') }}"
                                active
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('reviews_nav_start')

                        @if ($module->app_reviews->data)
                            <x-tabs.nav
                                id="reviews"
                                name="{{ trans('modules.tab.reviews') }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('installation_nav_start')

                        @if ($module->installation)
                            <x-tabs.nav
                                id="installation"
                                name="{{ trans('modules.tab.installation') }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('documentation_nav_start')

                        @if ($module->documentation)
                            <x-tabs.nav
                                id="documentation"
                                name="{{ trans('modules.documentation') }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('screenshots_nav_start')

                        @if ($module->screenshots)
                            <x-tabs.nav
                                id="screenshots"
                                name="{{ trans('modules.tab.screenshots') }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('changelog_nav_start')

                        @if ($module->changelog)
                            <x-tabs.nav
                                id="changelog"
                                name="{{ trans('modules.tab.changelog') }}"
                                class="relative px-8 text-sm text-black text-center pb-2 cursor-pointer transition-all border-b tabs-link"
                            />
                        @endif

                        @stack('changelog_nav_end')
                    </x-slot>

                    <x-slot name="content">
                        <div class="pt-4">
                            @stack('features_tab_start')

                            @if ($module->call_to_actions)
                                <x-tabs.tab id="features">
                                    <x-layouts.modules.show.features :module="$module" />
                                </x-tabs.tab>
                            @else
                                <x-tabs.tab id="description">
                                    <x-layouts.modules.show.description :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('reviews_tab_start')

                            @if ($module->app_reviews->data)
                                <x-tabs.tab id="reviews">
                                    <x-layouts.modules.show.reviews :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('installation_tab_start')

                            @if ($module->installation)
                                <x-tabs.tab id="installation">
                                    <x-layouts.modules.show.installation :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('documentation_tab_start')

                            @if ($module->documentation)
                                <x-tabs.tab id="documentation">
                                    <x-layouts.modules.show.documentation :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('screenshots_tab_start')

                            @if ($module->screenshots)
                                <x-tabs.tab id="screenshots">
                                    <x-layouts.modules.show.screenshots :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('changelog_tab_start')

                            @if ($module->changelog)
                                <x-tabs.tab id="changelog">
                                    <x-layouts.modules.show.releases :module="$module" />
                                </x-tabs.tab>
                            @endif

                            @stack('changelog_tab_end')
                        </div>
                    </x-slot>
                </x-tabs>
            </div>
        </div>

        @if ($module->install)
            <akaunting-modal
                :show="installation.show"
                title="{{ trans('modules.installation.header') }}"
                @cancel="installation.show = false"
            >
                <template #modal-body>
                    <div class="py-1 px-5 bg-body h-5/6 overflow-y-auto">
                        <el-progress :text-inside="true" :stroke-width="24" :percentage="installation.total" :status="installation.status"></el-progress>
                        <div id="progress-text" class="mt-3" v-html="installation.html"></div>
                    </div>
                </template>

                <template #card-footer>
                    <span></span>
                </template>
            </akaunting-modal>
        @endif
    </x-slot>

    @push('scripts_start')
        <script type="text/javascript">
            var app_slug = "{{ $module->slug }}";
        </script>
    @endpush

    <x-script folder="modules" file="apps" />
</x-layouts.modules>
