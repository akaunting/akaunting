<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.signed.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.signed.head>

    <body>
        @stack('body_start')

        <div class="container-fluid content-layout mt-4">
            <x-layouts.signed.header>
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
            </x-layouts.signed.header>

            <x-layouts.signed.content>
                <livewire:notification.browser />

                {!! $content !!}
            </x-layouts.signed.content>

            <x-layouts.signed.footer />
        </div>

        @stack('body_end')

        <x-layouts.signed.scripts />
    </body>
</html>
