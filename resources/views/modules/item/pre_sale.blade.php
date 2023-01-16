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
        <div class="flex flex-col gap-16 py-4">
            <div class="flex flex-col lg:flex-row w-full gap-8 lg:gap-16">
                <div class="w-full lg:w-7/12 flex flex-col gap-2 banner">
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
                                        <i class="material-icons text-purple text-7xl"> play_arrow </i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    @if ($module->categories)
                        <div class="flex justify-between">
                            <span>
                                @foreach ($module->categories as $module_category)
                                    <x-link href="{{ route('apps.categories.show', $module_category->slug) }}" class="text-sm" override="class">
                                        {{ $module_category->name }}
                                    </x-link>
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>

                <div class="w-full lg:w-5/12 flex flex-col justify-between">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col">
                            <div class="flex flex-col gap-4">
                                @if ($module->vote)
                                    <div class="flex items-center gap-4">
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

                                <div class="flex flex-col gap-1">
                                    <div class="flex gap-4 items-baseline">
                                        <h3 class="text-4xl font-semibold text-black">
                                            {{ $module->name }}
                                        </h3>

                                        @if ($module->vendor_name)
                                            <span class="text-sm">
                                                 by <a class="border-b border-dashed border-black transition-all hover:font-semibold" href="{{ route('apps.vendors.show', $module->vendor->slug) }}">
                                                    {{ $module->vendor_name }}
                                                </a>
                                            </span>
                                        @endif
                                    </div>

                                    @if ($module->version)
                                        <div class="text-xs">
                                            <span>{{ trans('footer.version') }} {{ $module->version }}</span>

                                            @if ($module->updated_at)
                                                <span> ( {{ trans('modules.updated') }} {{ Date::parse($module->updated_at)->diffForHumans() }} )</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div
                            class="text-sm whitespace-normal truncate line-clamp-3"
                        >
                            {!! $module->description !!}
                        </div>

                        <div class="flex items-baseline">
                            <span class="text-5xl font-bold text-purple">
                                @if ($module->price == '0.0000')
                                    {{ trans('modules.free') }}
                                @else
                                    {!! $module->price_prefix !!}

                                @if (isset($module->special_price))
                                    <del>{{ $module->price }}</del>
                                    {{ $module->special_price }}
                                @else
                                    {{ $module->price }}
                                @endif
                                    {!! $module->price_suffix !!}
                                @endif
                            </span>

                            <span class="text-sm mb-0"> / {{ trans('modules.billed_yearly') }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col mt-5">
                        @can('create-modules-item')
                            @if ($module->install)
                                <x-tooltip id="tooltip-countdown-install" placement="bottom" message="{{ trans('modules.pre_sale_install') }}">
                                    <x-button
                                        kind="primary"
                                        override="class"
                                        class="w-full px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm text-white font-medium leading-6 cursor-default bg-green hover:bg-green-700 disabled:bg-gray-50"
                                    >
                                        <akaunting-countdown id="countdown-pre-sale"
                                            :year="{{ (int) $module->pre_sale_date->year }}"
                                            :month="{{ (int) $module->pre_sale_date->month - 1 }}"
                                            :date="{{ (int) $module->pre_sale_date->day }}"
                                        ></akaunting-countdown>
                                    </x-button>
                                </x-tooltip>
                            @else
                                <x-tooltip id="tooltip-countdown-uninstall" placement="bottom" message="{{ trans('modules.pre_sale_uninstall') }}">
                                    <x-link
                                        href="/"
                                        kind="primary"
                                        override="class"
                                        class="w-full flex items-center justify-center px-3 py-1.5 mb-3 sm:mb-0 rounded-xl text-sm font-medium leading-6 bg-green hover:bg-green-700 text-white disabled:bg-green-100"
                                    >
                                    <akaunting-countdown id="countdown-pre-sale"
                                        :year="{{ (int) $module->pre_sale_date->year }}"
                                        :month="{{ (int) $module->pre_sale_date->month - 1 }}"
                                        :date="{{ (int) $module->pre_sale_date->day }}"
                                    ></akaunting-countdown>
                                    </x-link>
                                </x-tooltip>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        
        @if ($module->purchase_faq)
            <akaunting-modal :show="faq" modal-dialog-class="max-w-screen-md">
                <template #modal-content>
                    {!! $module->purchase_faq !!}
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
