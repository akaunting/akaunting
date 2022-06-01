<!DOCTYPE html>
<html dir="{{ language()->direction() }}" lang="{{ app()->getLocale() }}">
    <x-layouts.print.head>
        <x-slot name="title">
            {!! !empty($title->attributes->has('title')) ? $title->attributes->get('title') : $title !!}
        </x-slot>
    </x-layouts.print.head>

    <body onload="window.print();">
        @stack('body_start')

        <x-layouts.print.content>
            {!! $content !!}
        </x-layouts.print.content>

        @stack('body_end')

        <x-layouts.print.scripts />
    </body>
</html>
