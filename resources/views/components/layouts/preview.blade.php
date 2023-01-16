<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.preview.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.preview.head>

    <body>
        @stack('body_start')

        <div class="flex flex-col h-screen">
            <header class="py-5 bg-purple-lighter text-purple text-center">
                <div class="w-full lg:max-w-6xl m-auto flex flex-col lg:flex-row items-center justify-between px-4 lg:px-0">
                    <div class="flex flex-col items-center lg:items-start">
                        <span class="font-medium uppercase">
                            {{ trans('general.preview_mode') }}
                        </span>

                        <span>
                            {!! !empty($sticky->attributes->has('description')) ? $sticky->attributes->get('description') : trans('invoices.sticky.description') !!}
                        </span>
                    </div>

                    <x-link href="{!! !empty($sticky->attributes->has('url')) ? $sticky->attributes->get('url') : route('dashboard') !!}" class="px-3 py-1.5 mt-5 lg:mt-0 rounded-xl text-sm font-medium leading-6 bg-purple hover:bg-purple-700 text-white disabled:bg-purple-100" override="class">
                        {{ trans('general.go_back', ['type' => company()->name]) }}
                    </x-link>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-5">
                <div class="w-full lg:max-w-6xl m-auto">
                    <x-layouts.preview.header>
                        <x-slot name="title">
                            {!! ! empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
                        </x-slot>

                        @if (! empty($status))
                            <x-slot name="status">
                                {!! $status !!}
                            </x-slot>
                        @endif

                        @if (! empty($info))
                            <x-slot name="info">
                                {!! $info !!}
                            </x-slot>
                        @endif

                        @if (! empty($favorite) || (! empty($favorite) && $favorite->attributes->has('title')))
                            @if (! $favorite->attributes->has('title'))
                                <x-slot name="favorite">
                                    {!! $favorite !!}
                                </x-slot>
                            @else
                                @php
                                    $favorite = [
                                        'title' => $favorite->attributes->get('title'),
                                        'icon' => $favorite->attributes->get('icon'),
                                        'route' => !empty($favorite->attributes->has('route')) ? $favorite->attributes->get('route') : '',
                                        'url' => !empty($favorite->attributes->has('url')) ? $favorite->attributes->get('url') : '',
                                    ];
                                @endphp

                                <x-slot name="favorite">
                                    <x-menu.favorite
                                        :title="$favorite['title']"
                                        :icon="$favorite['icon']"
                                        :route="$favorite['route']"
                                        :url="$favorite['url']"
                                    />
                                </x-slot>
                            @endif
                        @endif

                        <x-slot name="buttons">
                            {!! $buttons ?? '' !!}
                        </x-slot>

                        <x-slot name="moreButtons">
                            {!! $moreButtons ?? '' !!}
                        </x-slot>
                    </x-layouts.preview.header>

                    <x-layouts.preview.content>
                        {!! $content !!}
                    </x-layouts.preview.content>

                    <x-layouts.preview.footer />
                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.preview.scripts />
    </body>
</html>
