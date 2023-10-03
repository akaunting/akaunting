<x-show.accordion type="restore" :open="($accordionActive == 'restore')">
    <x-slot name="head">
        <x-show.accordion.head
            title="{{ trans('general.restore') }}"
            description="{!! trans($description, [
                'user' => $user_name,
                'type' => $type_lowercase,
                'date' => $last_cancelled_date,
            ]) !!}"
        />
    </x-slot>

    <x-slot name="body">
        <div class="flex">
            <x-link href="{{ route($restoreRoute, $document->id) }}" id="show-slider-actions-restore-{{ $document->type }}" @click="e => e.target.classList.add('disabled')">
                {{ trans('general.restore') }}
            </x-link>
        </div>
    </x-slot>
</x-show.accordion>
