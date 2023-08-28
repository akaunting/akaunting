<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.auth.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.auth.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <div id="app" class="h-screen lg:h-auto bg-no-repeat bg-cover bg-center" style="background-image: url({{ asset('public/img/auth/login-bg.png') }});">
            <div class="relative w-full lg:max-w-7xl flex items-center m-auto">
                <x-layouts.auth.slider>
                    {!! $slider ?? '' !!}
                </x-layouts.auth.slider>

                <x-layouts.auth.content>
                    {!! $content !!}

                    <x-layouts.auth.footer />
                </x-layouts.auth.content>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.auth.scripts />
    </body>
</html>
