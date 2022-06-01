@stack('header_start')

<div id="header" class="xl:pt-6">
    <div class="flex flex-col sm:flex-row flex-wrap items-start justify-between hide-empty-page">
        <div class="w-full sm:w-6/12 items-center mb-3 sm:mb-0">
            <div class="flex items-center">
                <h1 class="flex items-center text-2xl xl:text-5xl text-black font-light -ml-0.5">
                    {!! $title !!}

                    @yield('dashboard_action')
                </h1>

                {!! $status ?? '' !!}

                {!! $favorite ?? '' !!}
            </div>
        </div>

        <div class="w-full sm:w-6/12">
            <div class="flex flex-wrap flex-col sm:flex-row sm:items-center justify-end sm:space-x-2 sm:rtl:space-x-reverse">
                @stack('header_button_start')

                {!! $buttons !!}

                @stack('header_button_end')

                {!! $moreButtons !!}
            </div>
        </div>
    </div>
</div>

@stack('header_end')
