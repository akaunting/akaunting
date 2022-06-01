@if ($layout == 'signed')
    <x-layouts.signed>
        <x-slot name="title">
            {{ trans('portal.payment_received') }}
        </x-slot>

        <x-slot name="buttons">
            <x-layouts.portal.finish.buttons :invoice="$invoice" />
        </x-slot>

        <x-slot name="content">
            <x-layouts.portal.finish.content :invoice="$invoice" />
        </x-slot>
    </x-layouts.signed>
@else
    <x-layouts.portal>
        <x-slot name="title">
            {{ trans('portal.payment_received') }}
        </x-slot>

        <x-slot name="buttons">
            <x-layouts.portal.finish.buttons :invoice="$invoice" />
        </x-slot>

        <x-slot name="content">
            <x-layouts.portal.finish.content :invoice="$invoice" />
        </x-slot>
    </x-layouts.portal>
@endif
