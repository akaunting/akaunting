<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.modules.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.modules.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <x-layouts.admin.menu />

        <!-- loading component will add this line -->
        <x-loading.content />
             
        <div class="main-content xl:ltr:ml-64  xl:rtl:mr-64 transition-all ease-in-out" id="panel">
            <div id="main-body">
                <div class="container">
                    <x-layouts.admin.header>
                        <div class="flex flex-row justify-items-center align-baseline">
                            <x-slot name="title">
                                {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
                            </x-slot>

                            @if (!empty($favorite) || (!empty($favorite) && $favorite->attributes->has('title')))
                                @if (! $favorite->attributes->has('title'))
                                    <x-slot name="favorite">
                                        {!! $favorite ?? '' !!}
                                    </x-slot>
                                @else
                                    <x-menu.favorite
                                        title="{!! $favorite->attributes->get('title') !!}"
                                        icon="{{ $favorite->attributes->get('icon') }}"

                                        @if (!empty($favorite->attributes->has('route')))
                                        route="{{ $favorite->attributes->get('route') }}"
                                        @endif

                                        @if (!empty($favorite->attributes->has('url')))
                                        url="{{ $favorite->attributes->get('url') }}"
                                        @endif
                                    />
                                @endif
                            @endif

                            <x-slot name="buttons">
                                {!! $buttons ?? '' !!}
                            </x-slot>

                            <x-slot name="moreButtons">
                                {!! $moreButtons ?? '' !!}
                            </x-slot>
                        </div>
                    </x-layouts.admin.header>

                    <x-layouts.admin.content>
                        <livewire:notification.browser />

                        <x-layouts.admin.notifications />

                        @if (! $content->attributes->has('withoutBar'))
                            <x-layouts.modules.bar />
                        @endif

                        {!! $content !!}
                    </x-layouts.admin.content>

                    <x-layouts.admin.footer />
                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.admin.scripts />
    </body>
</html>
