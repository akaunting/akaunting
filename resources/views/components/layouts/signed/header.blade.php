@stack('header_start')

<div id="header" class="xl:pt-6 -mt-2">
    <div lass="flex flex-col sm:flex-row items-start justify-between sm:space-x-4 hide-empty-page">
        <div data-page-title-first class="w-full sm:w-6/12 items-center mb-3 sm:mb-0">
            <div class="flex items-center space-y-4">
                <h1 class="flex items-center text-2xl xl:text-5xl text-black font-light -ml-0.5 mt-2 whitespace-nowrap">
                    <x-title>
                        {!! $title !!}
                    </x-title>

                    @yield('dashboard_action')
                </h1>

                @if (! empty($status))
                <div class="ltr:ml-4 rtl:mr-4 -mt-4">
                    {!! $status !!}
                </div>
                @endif

                {!! $info ?? '' !!}

                {!! $favorite ?? '' !!}
            </div>
        </div>

        <div data-page-title-second class="w-full flex flex-wrap flex-col sm:flex-row sm:items-center justify-end sm:space-x-2 sm:rtl:space-x-reverse suggestion-buttons">
            @stack('header_button_start')

            {!! $buttons !!}

            @stack('header_button_end')

            {!! $moreButtons !!}
        </div>
    </div>
</div>

@stack('header_end')
