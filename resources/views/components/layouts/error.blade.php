<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.admin.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.admin.head>

    @mobile
    <body class="bg-body">
    @elsemobile
    <body class="bg-body overflow-y-overlay">
    @endmobile

        @stack('body_start')

        <div class="main-content xl:ltr:ml-64  xl:rtl:mr-64 transition-all ease-in-out" id="panel">
            <div id="main-body">
                <div class="container">
                    <x-layouts.admin.content>
                        {!! $content !!}
                    </x-layouts.admin.content>

                </div>
            </div>
        </div>

        @stack('body_end')

        <x-layouts.admin.scripts />
    </body>
</html>
