<div class="flex flex-col gap-6 mb-8">
    <div class="relative w-full mb-36 lg:mb-0">
        <img src="{{ asset('public/img/empty_pages/no-apps.png') }}" class="w-full" />

        <div class="absolute inset-0 flex flex-col top-1/4 items-center gap-4">
            <h1 class="text-xl lg:text-5xl text-center text-black font-semibold">
                {{ trans('modules.no_apps_marketing') }}
            </h1>

            <p class="w-1/2 text-center text-black">
                {{ trans('modules.no_apps') }}
            </p>

            <x-link href="{{ route('apps.home.index') }}" class="px-3 py-1 bg-green rounded-md text-white" override="class">
                {{ trans('modules.see_all') }}
            </x-link>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row col-span-3 gap-y-8 gap-x-12 ltr:pl-8 rtl:pr-8 my-8">
        @foreach ($modules as $item)
            <div>
                <div class="relative right-10 bottom-4">
                    <i class="absolute material-icons text-purple transform rotate-180 text-7xl">format_quote</i> 
                </div>

                <div class="flex flex-col gap-y-6">
                    <p class="font-semibold text-sm text-left leading-loose">
                        {!! nl2br($item->text) !!}
                    </p>

                    <div class="flex place-items-center">
                        <img src="{{ $item->thumb }}" class="w-12 h-12 object-cover" />

                        <div class="flex flex-col ltr:ml-2 rtl:mr-2">
                            <span>{{ $item->author }}</span>
                            <span class="font-thin">{{ $item->country }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
