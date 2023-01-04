<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.portal.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.portal.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <x-layouts.portal.menu />

        <div class="main-content xl:ltr:ml-64  xl:rtl:mr-64 transition-all ease-in-out" id="panel">
            <div id="main-body">
                <div class="container">
                    <x-layouts.portal.header>
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
                    </x-layouts.portal.header>

                    <x-layouts.portal.content>
                        <notifications.browser />

                        {!! $content !!}
                    </x-layouts.portal.content>

                    <x-layouts.portal.footer />
                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.portal.scripts />
    </body>
</html>
