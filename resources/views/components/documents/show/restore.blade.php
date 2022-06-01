<x-show.accordion type="restore" :open="($accordionActive == 'create')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.restore') }}"
            description="{!! $description !!}"
        />
    </x-slot>

    <x-slot name="body">
        <x-button>
            {{ trans('general.restore') }}
        </x-button>
    </x-slot>
</x-show.accordion>
