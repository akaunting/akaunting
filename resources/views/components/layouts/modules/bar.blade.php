<div class="relative w-full flex border-b pt-8 mb-4">
    <div class="flex flex-row items-center place-center border-r">
        <button class="flex items-center" id="dropdownButton" data-dropdown-toggle="dropdown">
            <i class="material-icons w-10 flex items-center aspect-square text-light-gray ltr:pl-2 rtl:pr-2 hover:text-gray-700"> apps_outlined </i>
        </button>

        <div id="dropdown" data-click-outside-none class="w-full px-0 hidden z-10">
            <div class="flex flex-col md:flex-row">
                <div class="w-full lg:w-6/12 flex flex-col shadow-md bg-white px-4 lg:pl-8 py-8 gap-2 rounded-l-xl">
                    <h4 class="capitalize font-thin">
                        {{ trans_choice('general.categories', 1) }}
                    </h4>

                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($categories as $slug => $category)
                            <x-link href="{{ $categoryUrl($slug) }}" class="font-semibold text-sm ltr:pr-4 rtl:pl-4 lg:pr-0 truncate bg-transparent" override="class">
                                <x-link.hover>
                                    {{ $category }}
                                </x-link.hover>
                            </x-link>
                        @endforeach
                    </div>
                </div>

                <div class="w-full lg:w-6/12 flex flex-col shadow-md bg-purple-lighter px-4 lg:px-12 py-8 gap-2 rounded-r-xl">
                    <h4 class="capitalize font-thin mb-2">
                        {{ trans('modules.popular_this_week') }}
                    </h4>

                    @if ($popular)
                        <div class="flex flex-col gap-4">
                            @foreach ($popular->data as $item)
                                <div class="hover:shadow-2xl rounded-lg">
                                    <a href="{{ route('apps.app.show', $item->slug) }}" class="flex items-center p-2">
                                        @foreach ($item->files as $file)
                                            @if (($file->media_type == 'image') && ($file->pivot->zone == 'thumbnail'))
                                                <img src="{{ $file->path_string }}" alt="{{ $item->name }}" class="w-28 h-20 rounded-md object-cover ltr:mr-3 rtl:ml-3" />
                                            @endif
                                        @endforeach

                                        <div class="flex flex-col py-1 gap-0">
                                            <h6 class="text-sm font-bold capitalize">
                                                {!! $item->name !!}
                                            </h6>

                                            <div class="line-clamp-2">
                                                <p class="font-thin text-xs text-left">
                                                    {!! $item->description !!}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row w-full justify-between">
        <div class="h-full relative">
            <form method="GET" action="{{ url("/" . company_id()) }}/apps/search">
                <div class="h-full flex items-center pl-2 gap-2">
                    <i class="material-icons text-light-gray">search</i>

                    <input
                        type="text"
                        name="keyword"
                        class="w-full bg-transparent text-black text-sm border-0 pr-10 pl-0 pb-2 focus:outline-none focus:ring-transparent focus:border-purple"
                        value="{{ isset($keyword) ? $keyword : '' }}"
                        placeholder="{{ trans('general.search_placeholder') }}"
                        autocomplete="off"
                        v-on:keyup="onLiveSearch($event)"
                    />
                </div>
            </form>
        </div>

        <div class="flex flex-row items-end lg:items-center mb-1 divide-x divide-black-400">
            <x-link href="{{ route('apps.home.index') }}" class="text-sm font-semibold px-2 sm:mt-0 sm:mb-0 leading-4" override="class">
                <x-link.hover color="to-black-400">
                    {{ trans('modules.home') }}
                </x-link.hover>
            </x-link>

            <x-link href="{{ route('apps.new') }}" class="text-sm font-semibold px-2 sm:mt-0 sm:mb-0 leading-4" override="class">
                <x-link.hover color="to-black-400">
                    {{ trans('modules.new') }}
                </x-link.hover>
            </x-link>

            <x-link href="{{ route('apps.paid') }}" class="text-sm font-semibold px-2 sm:mt-0 sm:mb-0 leading-4" override="class">
                <x-link.hover color="to-black-400">
                    {{ trans('modules.top_paid') }}
                </x-link.hover>
            </x-link>

            <x-link href="{{ route('apps.free') }}" class="text-sm font-semibold px-2 sm:mt-0 sm:mb-0 leading-4" override="class">
                <x-link.hover color="to-black-400">
                    {{ trans('modules.top_free') }}
                </x-link.hover>
            </x-link>
        </div>
    </div>

    <div ref="liveSearchModal" v-if="live_search_modal" class="absolute w-full bg-white rounded-xl shadow-md p-4 top-20 z-10">
        <ul class="grid sm:grid-cols-6 gap-8">
            <li v-for="(item, index) in live_search_data" :key="index" class="sm:col-span-3 p-3 rounded-lg hover:bg-gray-100">
                <a :href="route_url + '/apps/' + item.slug" class="flex items-center space-x-4">
                    <img v-for="(file, indis) in item.files"
                        :src="file.path_string"
                        :alt="item.name"
                        class="w-12 h-12 rounded-lg object-cover"
                    />
                    <div>
                        <h6 class="font-bold" v-html="item.name"></h6>
                        <span class="text-sm text-gray-500 line-clamp-3" v-html="item.description"></span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>