<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.install.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.install.head>

    <body>
        @stack('body_start')

        <div class="h-screen lg:h-auto bg-no-repeat bg-cover bg-center" style="background-image: url({{ asset('public/img/auth/login-bg.png') }});">
            @if (! file_exists(public_path('js/install.min.js')))
                <div class="relative w-full lg:max-w-7xl flex flex-col lg:flex-row items-center m-auto">
                    <div class="md:w-6/12 h-screen hidden lg:flex flex-col items-center justify-center">
                        <img src="{{ asset('public/img/empty_pages/transactions.png') }}" alt="" />
                    </div>

                    <div class="w-full lg:w-46 h-31 flex flex-col justify-center gap-12 px-6 lg:px-24 py-24 mt-12 lg:mt-0">
                        <div class="flex flex-col gap-4">
                            <img src="{{ asset('public/img/akaunting-logo-green.svg') }}" class="w-16 my-3" alt="Akaunting" />

                            <div class="rounded-xl px-5 py-3 mb-5 bg-red-100 text-sm mb-0 text-red-600">
                                {!! trans('install.requirements.npm') !!}
                            </div>
                        </div>
                    </div>
                </div>
            @else
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
            @endif
        </div>

        @stack('body_end')

        <x-layouts.install.scripts />
    </body>
</html>
