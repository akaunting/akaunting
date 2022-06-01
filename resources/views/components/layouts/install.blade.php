<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.install.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.install.head>

    <body>
        @stack('body_start')

        <div class="bg-no-repeat bg-cover bg-center" style="background-image: url({{ asset('public/img/auth/login-bg.png') }});">
            <div class="relative w-full lg:max-w-7xl flex items-center m-auto">
                <x-layouts.auth.slider>
                    {!! $slider ?? '' !!}
                </x-layouts.auth.slider>

                <div class="w-full lg:w-46 h-31 flex flex-col justify-center gap-12 px-6 lg:px-24 py-24 mt-12 lg:mt-0">
                    <div class="flex flex-col gap-4">
                        <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16 my-3" alt="Akaunting" />

                        <x-layouts.install.content :title="$title">
                            {!! $content !!}
                        </x-layouts.install.content>
                    </div>
                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.install.scripts />
    </body>
</html>
