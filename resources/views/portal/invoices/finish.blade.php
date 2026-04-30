@if ($layout == 'signed')
<div class="w-full lg:max-w-6xl px-4 lg:px-0 m-auto">
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
</div>
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
