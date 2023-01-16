<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.wizard.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.wizard.head>

    <body class="h-screen flex justify-center items-center">
        @stack('body_start')

        <x-layouts.wizard.background />

        <div class="container">
            <x-layouts.wizard.content>
                {!! $content !!}
            </x-layouts.wizard.content>
        </div>

        @stack('body_end')

        <x-layouts.wizard.scripts />
    </body>
</html>
